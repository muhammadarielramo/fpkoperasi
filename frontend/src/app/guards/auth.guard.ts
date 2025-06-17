import { Injectable } from '@angular/core';
import { CanActivate, Router } from '@angular/router';
import { ToastController } from '@ionic/angular';
import { AuthService } from '../services/auth.service';

@Injectable({
  providedIn: 'root',
})
export class AuthGuard implements CanActivate {
  constructor(
    private authService: AuthService,
    private router: Router,
    private toastController: ToastController
  ) {}

  /**
   * Metode ini akan berjalan secara otomatis setiap kali
   * sebuah rute yang dilindungi oleh guard ini akan diakses.
   */
  async canActivate(): Promise<boolean> {
    const isLoggedIn = await this.authService.isLoggedIn();

    if (isLoggedIn) {
      return true; // Jika pengguna sudah login, izinkan akses.
    } else {
      // Jika tidak, tampilkan pesan dan arahkan ke halaman login.
      await this.presentToast('Anda harus login untuk mengakses halaman ini.');
      this.router.navigate(['/login']);
      return false; // Blokir akses ke halaman.
    }
  }

  async presentToast(message: string) {
    const toast = await this.toastController.create({
      message: message,
      duration: 3000,
      position: 'top',
      color: 'warning',
    });
    await toast.present();
  }
}
