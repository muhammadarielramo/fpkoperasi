import { Component, OnInit } from '@angular/core';
import { ToastController } from '@ionic/angular';
import { DepositService } from 'src/app/services/deposit.service';
import { LoanService } from 'src/app/services/loan.service';
import { register } from 'swiper/element/bundle';

// Daftarkan elemen swiper
register();

@Component({
  standalone: false,
  selector: 'app-dashboard',
  templateUrl: './dashboard.page.html',
  styleUrls: ['./dashboard.page.scss'],
})
export class DashboardPage implements OnInit {
  public savings: any = {
    total_wajib: 0,
    total_pokok: 0,
    total_sukarela: 0,
  };
  public totalLoan: number = 0;
  public isLoading: boolean = true;

  constructor(
    private depositService: DepositService,
    private loanService: LoanService,
    private toastCtrl: ToastController
  ) {}

  ngOnInit() {}

  ionViewWillEnter() {
    this.loadDashboardData();
  }

  async loadDashboardData(event?: any) {
    this.isLoading = true;

    // Ambil data simpanan
    (await this.depositService.getMyDeposits()).subscribe({
      next: (res: any) => {
        // PERBAIKAN: Proses data simpanan yang diterima dari API
        if (res && res.data && Array.isArray(res.data.simpanan)) {
          // Reset nilai awal sebelum menghitung
          this.savings = { total_wajib: 0, total_pokok: 0, total_sukarela: 0 };

          // Ulangi setiap item di dalam array 'simpanan'
          res.data.simpanan.forEach((item: any) => {
            // Ubah string menjadi angka
            const amount = parseFloat(item.total_simpanan || 0);

            // Tambahkan ke total yang sesuai
            if (item.jenis_simpanan === 'wajib') {
              this.savings.total_wajib += amount;
            } else if (item.jenis_simpanan === 'pokok') {
              this.savings.total_pokok += amount;
            } else if (item.jenis_simpanan === 'sukarela') {
              this.savings.total_sukarela += amount;
            }
          });
        }
      },
      error: (err) => this.presentErrorToast('Gagal memuat data simpanan.'),
    });

    // Ambil data pinjaman
    (await this.loanService.getMyLoans()).subscribe({
      next: (res: any) => {
        if (res && res.data) {
          let loansArray = [];
          if (Array.isArray(res.data)) {
            loansArray = res.data;
          } else if (typeof res.data === 'object' && res.data !== null) {
            loansArray = [res.data];
          }
          this.totalLoan = loansArray.reduce(
            (sum: number, loan: any) => sum + parseFloat(loan.jumlah_pinjaman || 0),
            0
          );
        } else {
          this.totalLoan = 0;
        }

        this.isLoading = false;
        if (event) event.target.complete();
      },
      error: (err) => {
        this.isLoading = false;
        if (event) event.target.complete();
        this.presentErrorToast('Gagal memuat data pinjaman.');
      },
    });
  }

  async presentErrorToast(message: string) {
    const toast = await this.toastCtrl.create({
      message: message,
      duration: 3000,
      position: 'top',
      color: 'danger',
    });
    await toast.present();
  }
}
