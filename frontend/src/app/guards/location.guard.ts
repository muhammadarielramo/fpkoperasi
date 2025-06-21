import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, CanActivate, Router } from '@angular/router';
import { ToastController } from '@ionic/angular';

@Injectable({
  providedIn: 'root'
})
export class LocationGuard implements CanActivate {

  constructor(
    private router: Router,
    private toastCtrl: ToastController
  ) { }

  async canActivate(route: ActivatedRouteSnapshot): Promise<boolean> {
    // 1. Dapatkan path untuk redirect dari data rute, dengan fallback
    const redirectPath = route.data['redirectTo'] || '/collector/dashboard';

    if (!navigator.permissions) {
      await this.presentToast('Perangkat tidak mendukung pengecekan izin.');
      return false; 
    }

    const permissionStatus = await navigator.permissions.query({ name: 'geolocation' });

    if (permissionStatus.state === 'granted') {
      return true; // Izin sudah diberikan, izinkan akses.
    }

    if (permissionStatus.state === 'prompt') {
      return this.requestPermission(redirectPath);
    } else { // denied
      await this.presentToast('Akses lokasi diperlukan untuk halaman ini.');
      // 2. Gunakan path redirect yang dinamis
      this.router.navigate([redirectPath]);
      return false;
    }
  }

  private async requestPermission(redirectPath: string): Promise<boolean> {
    try {
      await new Promise<void>((resolve, reject) => {
        navigator.geolocation.getCurrentPosition(
          () => resolve(),
          (error) => reject(error),
          { timeout: 10000 }
        );
      });
      return true;
    } catch (error) {
      await this.presentToast('Anda harus mengizinkan akses lokasi untuk melanjutkan.');
      // 3. Gunakan path redirect yang dinamis
      this.router.navigate([redirectPath]);
      return false;
    }
  }

  async presentToast(message: string) {
    const toast = await this.toastCtrl.create({
      message,
      duration: 3500,
      position: 'top',
      color: 'warning'
    });
    await toast.present();
  }
}
