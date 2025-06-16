import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, CanActivate, Router } from '@angular/router';
import { ToastController } from '@ionic/angular';
import { AuthService } from '../services/auth.service';

@Injectable({
  providedIn: 'root'
})
export class RoleGuard implements CanActivate {

  constructor(
    private authService: AuthService,
    private router: Router,
    private toastController: ToastController
  ) {}

  /**
   * canActivate sekarang menjadi 'async' dan mengembalikan Promise<boolean>
   * untuk menangani operasi asinkron dari AuthService.
   */
  async canActivate(route: ActivatedRouteSnapshot): Promise<boolean> {
    const expectedRole = route.data['role'];

    // 1. Cek status login secara asinkron
    const isLoggedIn = await this.authService.isLoggedIn();
    if (!isLoggedIn) {
      await this.presentToast('Anda harus login untuk mengakses halaman ini.');
      this.router.navigate(['/login']);
      return false;
    }

    // 2. Ambil peran pengguna secara asinkron
    const currentUserRole = await this.authService.getRole();

    // 3. Cek apakah peran pengguna sesuai dengan yang diharapkan
    if (currentUserRole !== expectedRole) {
      await this.presentToast(
        'Anda tidak memiliki izin untuk mengakses halaman ini.'
      );
      // Arahkan pengguna kembali ke dashboard mereka yang seharusnya,
      // atau ke halaman utama jika peran tidak valid.
      if (currentUserRole === '2') {
        this.router.navigate(['/collector/dashboard']);
      } else if (currentUserRole === '3') {
        this.router.navigate(['/member/dashboard']);
      } else {
        this.router.navigate(['/home']);
      }
      return false;
    }

    // Jika semua pengecekan lolos, izinkan akses
    return true;
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
