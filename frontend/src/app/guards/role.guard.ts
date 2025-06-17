import { Injectable } from '@angular/core';
import {
  ActivatedRouteSnapshot,
  CanActivate,
  Router,
} from '@angular/router';
import { ToastController } from '@ionic/angular';
import { AuthService } from '../services/auth.service';

@Injectable({
  providedIn: 'root',
})
export class RoleGuard implements CanActivate {
  constructor(
    private authService: AuthService,
    private router: Router,
    private toastController: ToastController
  ) {}

  async canActivate(route: ActivatedRouteSnapshot): Promise<boolean> {
    // Asumsikan AuthGuard sudah memastikan pengguna login,
    // jadi kita langsung cek perannya.
    const expectedRole = route.data['role'];
    const currentUserRole = await this.authService.getRole();

    // Cek apakah peran pengguna sesuai dengan yang diharapkan
    if (currentUserRole !== expectedRole) {
      await this.presentToast(
        'Anda tidak memiliki izin untuk mengakses halaman ini.'
      );
      // Arahkan pengguna kembali ke dashboard mereka yang seharusnya
      if (currentUserRole === '2') {
        this.router.navigate(['/collector/dashboard']);
      } else if (currentUserRole === '3') {
        this.router.navigate(['/member/dashboard']);
      } else {
        // Jika karena alasan aneh peran tidak ada, arahkan ke login
        this.router.navigate(['/login']);
      }
      return false;
    }

    // Jika peran cocok, izinkan akses
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
