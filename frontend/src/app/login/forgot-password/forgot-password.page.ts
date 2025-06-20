import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { LoadingController, ToastController } from '@ionic/angular';
import { AuthService } from '../../services/auth.service';

@Component({
  standalone: false,
  selector: 'app-forgot-password',
  templateUrl: './forgot-password.page.html',
  styleUrls: ['./forgot-password.page.scss'],
})
export class ForgotPasswordPage implements OnInit {
  forgotPasswordForm: FormGroup;
  showPasswordResetConfirm = false;

  constructor(
    private formBuilder: FormBuilder,
    private authService: AuthService,
    private loadingCtrl: LoadingController,
    private toastCtrl: ToastController
  ) {
    this.forgotPasswordForm = this.formBuilder.group({
      email: ['', [Validators.required, Validators.email]],
    });
  }

  ngOnInit() {}

  async handleResetPassword() {
    if (this.forgotPasswordForm.invalid) {
      this.presentToast('Silakan masukkan alamat email yang valid.');
      return;
    }

    const loading = await this.loadingCtrl.create({
      message: 'Mengirim permintaan...',
    });
    await loading.present();

    const email = this.forgotPasswordForm.value.email;

    this.authService.forgotPassword(email).subscribe({
      next: (res) => {
        loading.dismiss();
        // Tampilkan modal konfirmasi
        this.showPasswordResetConfirm = true;
      },
      error: (err) => {
        loading.dismiss();
        const message = err.error?.message || 'Gagal mengirim link reset. Coba lagi.';
        this.presentToast(message);
      },
    });
  }

  async presentToast(message: string) {
    const toast = await this.toastCtrl.create({
      message: message,
      duration: 3000,
      position: 'top',
      color: 'danger',
    });
    await toast.present();
  }
}
