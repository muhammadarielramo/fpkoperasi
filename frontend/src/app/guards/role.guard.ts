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
    // expectedRole sekarang akan berupa angka (mis: 2 atau 3)
    const expectedRole = route.data['role']; 
    const currentUserRole = await this.authService.getRole();

    // PERBAIKAN: Ubah peran dari storage menjadi angka untuk perbandingan
    // Memberi nilai default '0' jika null untuk mencegah error parseInt
    const roleAsNumber = parseInt(currentUserRole || '0', 10);

    if (roleAsNumber !== expectedRole) {
      await this.presentToast(
        'Anda tidak memiliki izin untuk mengakses halaman ini.'
      );
      
      // Arahkan pengguna kembali ke dashboard mereka yang seharusnya
      if (roleAsNumber === 2) {
        this.router.navigate(['/collector/dashboard']);
      } else if (roleAsNumber === 3) {
        this.router.navigate(['/member/dashboard']);
      } else {
        this.router.navigate(['/login']); // Fallback jika peran tidak ada
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
