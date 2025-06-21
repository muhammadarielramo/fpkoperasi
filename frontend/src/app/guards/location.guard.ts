import { Injectable } from '@angular/core';
import { CanActivate, Router, ActivatedRouteSnapshot } from '@angular/router';
import { ToastController } from '@ionic/angular';
import { Geolocation } from '@capacitor/geolocation';

@Injectable({
  providedIn: 'root'
})
export class LocationGuard implements CanActivate {

  constructor(
    private router: Router,
    private toastCtrl: ToastController
  ) { }

  async canActivate(route: ActivatedRouteSnapshot): Promise<boolean> {
    // Dapatkan path untuk redirect dari data rute, dengan fallback
    const redirectPath = route.data['redirectTo'] || '/collector/dashboard';

    try {
      // 1. Cek status izin saat ini menggunakan Capacitor
      const permissions = await Geolocation.checkPermissions();

      if (permissions.location === 'granted') {
        return true; // Izin sudah ada, langsung izinkan akses.
      }
      
      // 2. Jika izin belum diberikan atau sudah ditolak, kita coba minta lagi.
      // Ini akan menampilkan dialog izin asli di HP.
      const request = await Geolocation.requestPermissions();
      
      if (request.location === 'granted') {
        return true; // Izin berhasil diberikan oleh pengguna.
      } else {
        // Pengguna secara eksplisit menolak izin.
        await this.presentToast('Anda harus mengizinkan akses lokasi untuk melanjutkan.');
        this.router.navigate([redirectPath]);
        return false;
      }
    } catch (error) {
      // Terjadi error lain, misal GPS tidak aktif atau plugin tidak terinstal.
      await this.presentToast('Gagal memverifikasi lokasi. Pastikan GPS aktif.', 'danger');
      this.router.navigate([redirectPath]);
      return false;
    }
  }

  async presentToast(message: string, color: string = 'warning') {
    const toast = await this.toastCtrl.create({
      message,
      duration: 3500,
      position: 'top',
      color
    });
    await toast.present();
  }
}
