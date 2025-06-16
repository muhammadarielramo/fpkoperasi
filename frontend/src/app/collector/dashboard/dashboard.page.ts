import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from '../../services/auth.service';

@Component({
  standalone: false,
  selector: 'app-dashboard',
  templateUrl: './dashboard.page.html',
  styleUrls: ['./dashboard.page.scss'],
})

export class DashboardPage implements OnInit {
  public userName: string = 'Collector';
  public today: string = '';

  constructor(
    private authService: AuthService,
    private router: Router
  ) {}

  /**
   * ngOnInit sekarang menjadi async untuk bisa menggunakan await saat memanggil
   * fungsi lain yang juga async.
   */
  async ngOnInit() {
    await this.loadUsername(); // Tunggu hingga nama pengguna selesai dimuat
    this.setCurrentDate();
  }

  /**
   * Mengambil nama pengguna dari service.
   * Fungsi ini sekarang harus 'async' untuk menunggu hasil dari AuthService.
   */
  async loadUsername() {
    // Gunakan 'await' untuk menunggu Promise dari getUsername() selesai.
    // Beri nilai default jika hasilnya null atau undefined.
    this.userName = (await this.authService.getUsername()) || 'Collector';
  }


  /**
   * Mengatur tanggal hari ini untuk ditampilkan di header.
   */
  setCurrentDate() {
    const options: Intl.DateTimeFormatOptions = {
      day: 'numeric',
      month: 'long',
      year: 'numeric',
    };
    this.today = new Date().toLocaleDateString('id-ID', options);
  }

  /**
   * Menangani proses logout secara asinkron.
   */
  async logout() {
    // Tunggu hingga proses logout di service (pembersihan storage) selesai.
    await this.authService.logout();
    // Setelah itu, baru arahkan pengguna ke halaman login.
    this.router.navigate(['/login'], { replaceUrl: true });
  }
}
