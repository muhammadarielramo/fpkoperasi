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

  canActivate(route: ActivatedRouteSnapshot): boolean {
    const expectedRole = route.data['role']; // Ambil peran yang diharapkan dari data rute
    const currentUserRole = this.authService.getRole(); // Ambil peran pengguna saat ini

    // 1. Cek apakah pengguna sudah login
    if (!this.authService.isLoggedIn()) {
      this.presentToast('Anda harus login untuk mengakses halaman ini.');
      this.router.navigate(['/login']);
      return false;
    }

    // 2. Cek apakah peran pengguna sesuai dengan yang diharapkan
    if (currentUserRole !== expectedRole) {
      this.presentToast('Anda tidak memiliki izin untuk mengakses halaman ini.');
      // Arahkan pengguna kembali ke dashboard mereka yang seharusnya
      this.router.navigate([`/${currentUserRole}/dashboard`]);
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
      color: 'warning'
    });
    toast.present();
  }
}
