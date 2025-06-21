import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { LoadingController, ToastController } from '@ionic/angular';
import { AuthService } from '../../services/auth.service';
import { CollectorService } from '../../services/collector.service';
import { Geolocation } from '@capacitor/geolocation';

@Component({
  standalone: false,
  selector: 'app-dashboard',
  templateUrl: './dashboard.page.html',
  styleUrls: ['./dashboard.page.scss'],
})
export class DashboardPage implements OnInit {
  public userName: string = 'Collector';
  public today: string = '';

  // 2. Properti untuk menyimpan data dinamis
  public totalMembers: number = 0;
  public todaysVisits: any[] = [];
  public isLoading: boolean = true;

  constructor(
    private authService: AuthService,
    private collectorService: CollectorService,
    private router: Router,
    private loadingCtrl: LoadingController,
    private toastCtrl: ToastController,
  ) {}

  async ngOnInit() {
    await this.loadUsername();
    this.setCurrentDate();
  }

  // Gunakan ionViewWillEnter agar data di-refresh setiap kali halaman ditampilkan
  ionViewWillEnter() {
    this.loadDashboardData();
  }

  async loadDashboardData() {
    this.isLoading = true;

    // Ambil total anggota
    (await this.collectorService.getCoachedMembers()).subscribe({
      next: (res) => {
        if (res.data) {
          this.totalMembers = res.data.length;
        }
      },
      error: (err) => this.presentErrorToast('Gagal memuat total anggota.'),
    });

    // Ambil kunjungan hari ini
    (await this.collectorService.getTodaysVisits()).subscribe({
      next: (res) => {
        if (res.data) {
          this.todaysVisits = res.data;
        }
        this.isLoading = false; // Sembunyikan loading setelah data terakhir didapat
      },
      error: (err) => {
        this.isLoading = false;
        this.presentErrorToast('Gagal memuat data kunjungan.');
      },
    });
  }

  async loadUsername() {
    this.userName = (await this.authService.getUsername()) || 'Collector';
  }

  setCurrentDate() {
    const options: Intl.DateTimeFormatOptions = { day: 'numeric', month: 'long', year: 'numeric' };
    this.today = new Date().toLocaleDateString('id-ID', options);
  }

  async goToPaymentEntry(loanId: number) {
    try {
      const permissions = await Geolocation.checkPermissions();
      if (permissions.location !== 'granted') {
        const request = await Geolocation.requestPermissions();
        if (request.location !== 'granted') {
          throw new Error('Anda harus mengizinkan akses lokasi untuk melanjutkan.');
        }
      }
      // Jika izin sudah ada atau baru saja diberikan, lanjutkan navigasi
      this.router.navigate(['/collector/loans/payment-entry', loanId]);
    } catch (error: any) {
      this.presentToast(error.message || 'Gagal memverifikasi izin lokasi.', 'warning');
    }
  }

  async logout() {
    await this.authService.logout();
    this.router.navigate(['/login'], { replaceUrl: true });
  }

  async presentToast(message: string, color: string) {
    const toast = await this.toastCtrl.create({
      message,
      duration: 3000,
      position: 'top',
      color,
    });
    await toast.present();
  }

  async presentErrorToast(message: string) {
    const toast = await this.toastCtrl.create({
      message: message,
      duration: 3000,
      position: 'top',
      color: 'danger'
    });
    await toast.present();
  }
}
