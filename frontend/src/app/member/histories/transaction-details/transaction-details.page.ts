import { Location } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ToastController, LoadingController } from '@ionic/angular';
import { TransactionService } from 'src/app/services/transaction.service';
import { SlipService } from 'src/app/services/slip.service';
import { Browser } from '@capacitor/browser';

@Component({
  standalone: false,
  selector: 'app-transaction-details',
  templateUrl: './transaction-details.page.html',
  styleUrls: ['./transaction-details.page.scss'],
})
export class TransactionDetailsPage implements OnInit {
  public transactionDetail: any = null;
  public isLoading: boolean = true;
  private transactionId!: number;

  constructor(
    private location: Location,
    private route: ActivatedRoute,
    private transactionService: TransactionService,
    private slipService: SlipService,
    private toastCtrl: ToastController,
    private loadingCtrl: LoadingController
  ) {}

  ngOnInit() {
    const id = this.route.snapshot.paramMap.get('id');
    if (id) {
      this.transactionId = +id;
      this.loadTransactionDetails();
    } else {
      this.isLoading = false;
      this.presentToast('ID Transaksi tidak ditemukan.', 'danger');
    }
  }

  async loadTransactionDetails() {
    this.isLoading = true;
    (await this.transactionService.getDetail(this.transactionId)).subscribe({
      next: (res: any) => {
        if (res && res.data) {
          this.transactionDetail = res.data;
        } else {
          this.presentToast('Gagal memuat detail transaksi.');
        }
        this.isLoading = false;
      },
      error: (err: any) => {
        this.isLoading = false;
        this.presentToast('Terjadi kesalahan saat memuat data.');
      },
    });
  }

  /**
   * Helper untuk menentukan judul halaman secara dinamis.
   */
  getPageTitle(): string {
    if (!this.transactionDetail) return 'Detail Transaksi';
    // Gunakan id_deposit dari respons asli untuk menentukan jenis
    if (this.transactionDetail.id_deposit) return 'Detail Setoran Simpanan';
    return 'Detail Pembayaran Pinjaman';
  }
  
  /**
   * Helper untuk mendapatkan nominal transaksi yang benar.
   */
  getNominalValue(): number {
    if (!this.transactionDetail) return 0;
    // Gunakan 'jumlah' dari transaksi jika 'besar_ciclan' tidak ada
    return this.transactionDetail.besar_ciclan || this.transactionDetail.jumlah || 0;
  }
  
  goBack() {
    this.location.back();
  }

  async openMap() {
    const lat = this.transactionDetail?.location?.latitude;
    const lon = this.transactionDetail?.location?.longitude;

    if (lat && lon) {
      // Gunakan URL yang lebih umum untuk membuka aplikasi peta di seluler
      const mapUrl = `https://maps.google.com/?q=${lat},${lon}`;
      await Browser.open({ url: mapUrl });
    } else {
      this.presentToast('Data lokasi tidak tersedia untuk transaksi ini.', 'warning');
    }
  }

  async downloadSlip() {
    const loading = await this.loadingCtrl.create({ message: 'Membuat slip...' });
    await loading.present();

    (await this.slipService.generateSlip(this.transactionId)).subscribe({
      next: (res: any) => {
        loading.dismiss();
        if (res && res.slip_url) {
          window.open(res.slip_url, '_blank');
        } else {
          this.presentToast('Gagal mendapatkan URL slip.');
        }
      },
      error: (err: any) => {
        loading.dismiss();
        this.presentToast('Gagal membuat slip pembayaran.');
      },
    });
  }

  async presentToast(message: string, color: string = 'danger') {
    const toast = await this.toastCtrl.create({
      message,
      duration: 3000,
      position: 'top',
      color,
    });
    await toast.present();
  }
}
