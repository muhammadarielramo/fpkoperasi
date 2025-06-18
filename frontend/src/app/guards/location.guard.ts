import { Injectable } from '@angular/core';
import { CanActivate, Router } from '@angular/router';
import { ToastController } from '@ionic/angular';

@Injectable({
  providedIn: 'root'
})
export class LocationGuard implements CanActivate {

  constructor(
    private router: Router,
    private toastCtrl: ToastController
  ) { }

  async canActivate(): Promise<boolean> {
    // Cek apakah browser mendukung Permissions API
    if (!navigator.permissions) {
      await this.presentToast('Browser tidak mendukung pengecekan izin.');
      return false; // Blokir jika API tidak ada
    }

    // Cek status izin geolokasi
    const permissionStatus = await navigator.permissions.query({ name: 'geolocation' });

    if (permissionStatus.state === 'granted') {
      return true; // Izin sudah diberikan, izinkan akses.
    }

    // Jika izin 'prompt' (belum ditanya) atau 'denied' (ditolak)
    if (permissionStatus.state === 'prompt') {
      // Minta izin
      return this.requestPermission();
    } else {
      // Jika ditolak, tampilkan pesan dan blokir akses.
      await this.presentToast('Akses lokasi diperlukan untuk halaman ini. Aktifkan di pengaturan browser Anda.');
      this.router.navigate(['/collector/savings']); // Arahkan kembali
      return false;
    }
  }

  private async requestPermission(): Promise<boolean> {
    try {
      // Meminta izin menggunakan Geolocation API
      await new Promise<void>((resolve, reject) => {
        navigator.geolocation.getCurrentPosition(
          () => resolve(), // Sukses, berarti izin diberikan
          (error) => reject(error), // Gagal, berarti izin ditolak
          { timeout: 10000 }
        );
      });
      return true; // Jika promise resolve, izin diberikan.
    } catch (error) {
      await this.presentToast('Anda harus mengizinkan akses lokasi untuk melanjutkan.');
      this.router.navigate(['/collector/savings']); // Arahkan kembali jika ditolak
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
