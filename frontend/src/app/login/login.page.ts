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
    // Inisialisasi form dengan FormBuilder
    this.loginForm = this.formBuilder.group({
      username: ['', [Validators.required]],
      password: ['', [Validators.required, Validators.minLength(6)]],
    });
  }

  ngOnInit() {}

  togglePasswordVisibility() {
    this.showPassword = !this.showPassword;
  }
  async login() {
    // Cek jika form valid
    if (this.loginForm.invalid) {
      this.presentToast('Harap isi semua kolom dengan benar.');
      return;
    }

    // Tampilkan loading indicator
    const loading = await this.loadingController.create({
      message: 'Mohon tunggu...',
    });
    await loading.present();

    // Panggil service login
    this.authService.login(this.loginForm.value).subscribe({
      next: (res) => {
        loading.dismiss();
        this.presentToast('Login berhasil!');
        
        // **LOGIKA PENGALIHAN BERDASARKAN PERAN**
        const userRole = this.authService.getRole(); // Ambil peran dari service

        if (userRole === '3') {
          this.router.navigateByUrl('/member/dashboard', { replaceUrl: true });
        } else if (userRole === '2') {
          this.router.navigateByUrl('/collector/dashboard', { replaceUrl: true });
        } else {
          // Arahkan ke halaman default jika peran tidak dikenali
          this.presentToast('Peran tidak dikenali. Menuju halaman utama.');
          this.router.navigateByUrl('/home', { replaceUrl: true });
        }
      },
      error: (err) => {
        loading.dismiss();
        const message = err.error?.message || 'Username atau Password salah.';
        this.presentToast(message);
      }
    });
  }

  /**
   * Helper untuk menampilkan notifikasi toast.
   * @param message - Pesan yang akan ditampilkan.
   */
  async presentToast(message: string) {
    const toast = await this.toastController.create({
      message: message,
      duration: 3000,
      position: 'top',
      color: 'danger'
    });
    toast.present();
  }
}
