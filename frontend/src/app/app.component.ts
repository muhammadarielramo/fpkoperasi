// Tambahan import untuk isPlatform dan plugin NavigationBar
import { Component, OnInit, OnDestroy } from '@angular/core';
import { Platform, isPlatform } from '@ionic/angular'; // <-- TAMBAHKAN isPlatform
import { ScreenOrientation } from '@capacitor/screen-orientation';
import { NavigationBar } from '@capgo/capacitor-navigation-bar'; // <-- TAMBAHKAN import ini

@Component({
  standalone: false,
  selector: 'app-root',
  templateUrl: 'app.component.html',
  styleUrls: ['app.component.scss'],
})
export class AppComponent implements OnInit, OnDestroy {

  private orientationChangeListener: any = null;

  constructor(private platform: Platform) {}

  async ngOnInit() {
    await this.platform.ready();

    if (isPlatform('capacitor')) {
      // Panggil kedua fungsi inisialisasi untuk perangkat seluler
      this.lockScreenOrientation();
      this.setDarkNavigationStyle(); // <-- PANGGIL FUNGSI BARU DI SINI
    } else {
      console.log('Tidak berjalan di perangkat seluler (Capacitor), inisialisasi dilewati.');
    }
  }

  // --- FUNGSI BARU UNTUK BILAH NAVIGASI ---
  private setDarkNavigationStyle(): void {
    // Fungsi ini hanya relevan untuk Android
    if (isPlatform('android')) {
      NavigationBar.setNavigationBarColor({
        color: '#121212',      // Atur warna latar menjadi gelap
        darkButtons: true,    // Gunakan ikon navigasi yang terang (putih)
      }).catch(error => {
        console.error('Gagal mengatur warna bilah navigasi', error);
      });
      console.log('Warna bilah navigasi diatur ke mode gelap.');
    }
  }
  // --- AKHIR FUNGSI BARU ---

  private async lockScreenOrientation() {
    try {
      // Mengunci orientasi ke mode potret primer
      await ScreenOrientation.lock({ orientation: 'portrait-primary' });
      console.log('Orientasi layar dikunci ke potret primer.');

      // Listener untuk mendeteksi perubahan.
      this.orientationChangeListener = ScreenOrientation.addListener('screenOrientationChange', (info) => {
        console.log('Orientasi layar berubah menjadi:', info.type);
      });

    } catch (error) {
      console.error('Gagal mengunci orientasi layar:', error);
    }
  }

  async ngOnDestroy() {
    if (isPlatform('capacitor')) {
      // Hapus listener orientasi layar jika ada
      if (this.orientationChangeListener && typeof this.orientationChangeListener.remove === 'function') {
        try {
          await this.orientationChangeListener.remove();
          console.log('Listener orientasi layar berhasil dihapus.');
          this.orientationChangeListener = null;
        } catch (error) {
          console.error('Gagal menghapus listener orientasi layar:', error);
        }
      }
    }
  }
}