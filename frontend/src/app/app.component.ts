import { Component, OnInit, OnDestroy } from '@angular/core';
import { Platform } from '@ionic/angular';
import { ScreenOrientation } from '@capacitor/screen-orientation';

@Component({
  standalone: false,
  selector: 'app-root',
  templateUrl: 'app.component.html',
  styleUrls: ['app.component.scss'],
})
export class AppComponent implements OnInit, OnDestroy {

  private orientationChangeListener: any = null; // Variabel untuk menyimpan referensi listener

  constructor(private platform: Platform) {}

  async ngOnInit() {
    await this.platform.ready();

    if (this.platform.is('capacitor')) {
      try {
        // Mengunci orientasi ke mode potret primer
        await ScreenOrientation.lock({ orientation: 'portrait-primary' });
        console.log('Orientasi layar dikunci ke potret primer.');

        // (Opsional) Listener untuk mendeteksi perubahan.
        // 'info' akan memiliki properti 'type' yang berisi string orientasi.
        this.orientationChangeListener = ScreenOrientation.addListener('screenOrientationChange', (info) => {
          console.log('Orientasi layar berubah menjadi:', info.type);
          // Anda bisa menambahkan logika di sini jika diperlukan,
          // misalnya mencoba mengunci kembali jika terlepas (meskipun jarang terjadi).
          // if (info.type !== 'portrait-primary' && info.type !== 'portrait-secondary') {
          //   ScreenOrientation.lock({ orientation: 'portrait-primary' }).catch(err => {
          //     console.error('Gagal mengunci ulang orientasi:', err);
          //   });
          // }
        });

      } catch (error) {
        console.error('Gagal mengunci orientasi layar:', error);
      }
    } else {
      console.log('Tidak berjalan di perangkat seluler (Capacitor), penguncian orientasi dilewati.');
    }
  }

  async ngOnDestroy() {
    if (this.platform.is('capacitor')) {
      // Hapus listener jika sudah ditambahkan
      if (this.orientationChangeListener) {
        try {
          // Opsi 1: Jika listener memiliki metode remove() sendiri (umum di Capacitor v5+)
          if (typeof this.orientationChangeListener.remove === 'function') {
            await this.orientationChangeListener.remove();
          } else {
            // Opsi 2: Fallback ke removeAllListeners jika remove() per listener tidak ada
            // Ini akan menghapus SEMUA listener untuk 'screenOrientationChange'
            await ScreenOrientation.removeAllListeners();
          }
          console.log('Listener orientasi layar berhasil dihapus.');
          this.orientationChangeListener = null;
        } catch (error) {
          console.error('Gagal menghapus listener orientasi layar:', error);
          // Sebagai fallback tambahan jika semua gagal, coba removeAllListeners
           try {
             await ScreenOrientation.removeAllListeners();
             console.log('Semua listener orientasi layar berhasil dihapus (fallback).');
           } catch (e) {
             console.error('Gagal menghapus semua listener (fallback):', e);
           }
        }
      }
    }
  }
}