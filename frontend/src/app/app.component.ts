import { Component, OnDestroy, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Platform, AlertController, isPlatform } from '@ionic/angular';
import { App } from '@capacitor/app';
import { ScreenOrientation } from '@capacitor/screen-orientation';
import { NavigationBar } from '@capgo/capacitor-navigation-bar';

@Component({
  standalone: false,
  selector: 'app-root',
  templateUrl: 'app.component.html',
  styleUrls: ['app.component.scss'],
})
export class AppComponent implements OnInit, OnDestroy {

  private orientationChangeListener: any = null;

  constructor(
    private platform: Platform,
    private router: Router,
    private alertController: AlertController
  ) {
    this.initializeApp();
  }

  ngOnInit() { }

  ngOnDestroy() {
    // Bersihkan listener jika ada
    if (this.orientationChangeListener && typeof this.orientationChangeListener.remove === 'function') {
      this.orientationChangeListener.remove();
    }
  }

  initializeApp() {
    this.platform.ready().then(() => {
      this.platform.backButton.subscribeWithPriority(10, () => {
        this.handleBackButton();
      });

      if (isPlatform('capacitor')) {
        this.initializeNormalFeatures();
      } else {
        console.log('Tidak berjalan di perangkat seluler (Capacitor), inisialisasi UI saja.');
        this.initializeNormalFeatures();
      }
    });
  }

  private initializeNormalFeatures() {
    this.lockScreenOrientation();
    this.setDarkNavigationStyle();
  }

  private setDarkNavigationStyle(): void {
    if (isPlatform('android')) {
      NavigationBar.setNavigationBarColor({
        color: '#121212',
        darkButtons: true,
      }).catch(error => {
        console.error('Gagal mengatur warna bilah navigasi', error);
      });
    }
  }

  private async lockScreenOrientation() {
    try {
      await ScreenOrientation.lock({ orientation: 'portrait-primary' });
      this.orientationChangeListener = ScreenOrientation.addListener('screenOrientationChange', (info) => {
        console.log('Orientasi layar berubah:', info.type);
      });
    } catch (error) {
      console.error('Gagal mengunci orientasi layar:', error);
    }
  }

  /**
   * Fungsi utama untuk menangani logika tombol kembali.
   */
  async handleBackButton() {
    const url = this.router.url;

    // Skenario 1: Keluar dari aplikasi di halaman Dashboard
    if (url === '/member/dashboard' || url === '/collector/dashboard') {
      this.showExitConfirm();
    } 
    // Skenario 2: Kembali ke home dari halaman login/register
    else if (url === '/login' || url === '/register') {
      this.router.navigateByUrl('/home', { replaceUrl: true });
    }
    // Skenario 3: Kembali ke halaman sebelumnya untuk semua halaman lain
    else {
      // Cek jika ada riwayat navigasi di dalam aplikasi
      if (window.history.length > 1) {
        window.history.back();
      } else {
        // Fallback jika tidak ada riwayat, kembali ke home
        this.router.navigateByUrl('/home'); 
      }
    }
  }

  /**
   * Menampilkan dialog konfirmasi sebelum keluar dari aplikasi.
   */
  async showExitConfirm() {
    const alert = await this.alertController.create({
      header: 'Keluar Aplikasi',
      message: 'Apakah Anda yakin ingin keluar dari aplikasi?',
      buttons: [
        {
          text: 'Batal',
          role: 'cancel',
          handler: () => {},
        },
        {
          text: 'Keluar',
          handler: () => {
            App.exitApp();
          },
        },
      ],
    });

    await alert.present();
  }
}