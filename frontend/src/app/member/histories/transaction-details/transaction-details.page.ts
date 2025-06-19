import { Location } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ToastController, LoadingController } from '@ionic/angular';
import { TransactionService } from 'src/app/services/transaction.service';
import { SlipService } from 'src/app/services/slip.service';

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
          // Asumsi backend mengembalikan struktur data yang sudah diolah
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

  goBack() {
    this.location.back();
  }

  openMap() {
    const lat = this.transactionDetail?.lokasi?.latitude;
    const lon = this.transactionDetail?.lokasi?.longitude;

    if (lat && lon) {
      window.open(`https://www.google.com/maps?q=${lat},${lon}`, '_blank');
    } else {
      this.presentToast('Data lokasi tidak tersedia untuk transaksi ini.', 'warning');
    }
  }

  async downloadSlip() {
    const loading = await this.loadingCtrl.create({
      message: 'Membuat slip...',
    });
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
