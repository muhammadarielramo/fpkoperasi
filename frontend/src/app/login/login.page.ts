import { HttpErrorResponse } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { ToastController, LoadingController } from '@ionic/angular';
import { AuthService } from '../services/auth.service';

@Component({
  standalone: false,
  selector: 'app-login',
  templateUrl: './login.page.html',
  styleUrls: ['./login.page.scss'],
})
export class LoginPage implements OnInit {
  showPassword = false;
  loginForm: FormGroup;

  constructor(
    private formBuilder: FormBuilder,
    private authService: AuthService,
    private router: Router,
    private toastController: ToastController,
    private loadingController: LoadingController
  ) {
    this.loginForm = this.formBuilder.group({
      username: ['', [Validators.required]],
      password: ['', [Validators.required]],
    });
  }

  ngOnInit() {}

  togglePasswordVisibility() {
    this.showPassword = !this.showPassword;
  }

  async login() {
    if (this.loginForm.invalid) {
      this.presentToast('Harap isi semua kolom dengan benar.');
      return;
    }

    const loading = await this.loadingController.create({
      message: 'Mohon tunggu...',
    });
    await loading.present();

    this.authService.login(this.loginForm.value).subscribe({
      next: async (response) => {
        if (!response || !response.token) {
          await loading.dismiss();
          this.presentToast('Username atau Password salah.', 'danger');
          return;
        }

        await loading.dismiss();
        await this.presentToast('Login berhasil!', 'success');

        const userRole = await this.authService.getRole();
        const roleAsNumber = parseInt(userRole || '0', 10);

        if (roleAsNumber === 2) {
          this.router.navigateByUrl('/collector/dashboard', { replaceUrl: true });
        } else if (roleAsNumber === 3) {
          this.router.navigateByUrl('/member/dashboard', { replaceUrl: true });
        } else {
          await this.presentToast(
            `Peran tidak dikenali. Role ID: ${userRole}`,
            'warning'
          );
          this.router.navigateByUrl('/home', { replaceUrl: true });
        }
      },
      error: async (err: HttpErrorResponse) => {
        await loading.dismiss();
        
        // PERBAIKAN: Cek status error untuk membedakan masalah jaringan
        let message: string;
        if (err.status === 0) {
          // Jika status 0, ini adalah error jaringan/koneksi
          message = 'Tidak dapat terhubung ke server. Periksa koneksi internet Anda.';
        } else {
          // Jika ada status lain, gunakan pesan dari server atau pesan default
          message = err.error?.message || 'Username atau Password salah.';
        }
        
        this.presentToast(message, 'danger');
      },
    });
  }

  async presentToast(message: string, color: string = 'danger') {
    const toast = await this.toastController.create({
      message: message,
      duration: 3000,
      position: 'top',
      color: color,
    });
    await toast.present();
  }
}
