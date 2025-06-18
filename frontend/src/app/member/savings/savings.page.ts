import { Component, OnInit } from '@angular/core';
import { ToastController } from '@ionic/angular';
import { DepositService } from 'src/app/services/deposit.service';

@Component({
  standalone: false,
  selector: 'app-savings',
  templateUrl: './savings.page.html',
  styleUrls: ['./savings.page.scss'],
})
export class SavingsPage implements OnInit {
  public isLoading: boolean = true;
  // Struktur data untuk menyimpan total dan tanggal terakhir
  public savingsDetails: any = {
    wajib: { total: 0, lastDate: null },
    pokok: { total: 0, lastDate: null },
    sukarela: { total: 0, lastDate: null },
  };

  constructor(
    private depositService: DepositService,
    private toastCtrl: ToastController
  ) {}

  ngOnInit() {}

  // Gunakan ionViewWillEnter agar data selalu segar
  ionViewWillEnter() {
    this.loadSavingsDetails();
  }

  async loadSavingsDetails(event?: any) {
    this.isLoading = true;

    (await this.depositService.getMyDeposits()).subscribe({
      next: (res: any) => {
        if (res && res.data && Array.isArray(res.data.simpanan)) {
          this.processSavingsData(res.data.simpanan);
        }
        this.isLoading = false;
        if (event) event.target.complete();
      },
      error: (err) => {
        this.isLoading = false;
        if (event) event.target.complete();
        this.presentToast('Gagal memuat detail simpanan.');
        console.error(err);
      },
    });
  }

  processSavingsData(transactions: any[]) {
    // Reset data sebelum menghitung ulang
    this.savingsDetails = {
      wajib: { total: 0, lastDate: null },
      pokok: { total: 0, lastDate: null },
      sukarela: { total: 0, lastDate: null },
    };

    transactions.forEach(item => {
      const amount = parseFloat(item.total_simpanan || 0);
      const date = new Date(item.created_at);
      const type = item.jenis_simpanan;

      if (this.savingsDetails[type]) {
        // Tambahkan jumlah ke total
        this.savingsDetails[type].total += amount;
        
        // Perbarui tanggal terakhir jika tanggal saat ini lebih baru
        if (!this.savingsDetails[type].lastDate || date > this.savingsDetails[type].lastDate) {
          this.savingsDetails[type].lastDate = date;
        }
      }
    });
  }

  async presentToast(message: string) {
    const toast = await this.toastCtrl.create({
      message: message,
      duration: 3000,
      position: 'top',
      color: 'danger',
    });
    await toast.present();
  }
}
