import { Location } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ToastController, LoadingController, Platform } from '@ionic/angular';
import { TransactionService } from 'src/app/services/transaction.service';
import { SlipService } from 'src/app/services/slip.service';
import { Browser } from '@capacitor/browser';
import { Filesystem, Directory } from '@capacitor/filesystem';
import { LocalNotifications } from '@capacitor/local-notifications';

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
    private loadingCtrl: LoadingController,
    private platform: Platform
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
        const message = err.error?.message || 'Terjadi kesalahan saat memuat data.';
        this.presentToast(message);
      },
    });
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
    // PERBAIKAN: Gunakan 'lat' dan 'lng' sesuai respons API
    const lat = this.transactionDetail?.lokasi?.koordinat?.lat;
    const lon = this.transactionDetail?.lokasi?.koordinat?.lng;

    if (lat && lon) {
      const mapUrl = `https://maps.google.com/?q=${lat},${lon}`;
      await Browser.open({ url: mapUrl });
    } else {
      this.presentToast('Data lokasi tidak tersedia untuk transaksi ini.', 'warning');
    }
  }

  async downloadSlip() {
    const loading = await this.loadingCtrl.create({
      message: 'Mempersiapkan slip...',
    });
    await loading.present();

    try {
      const res = await (await this.slipService.generateSlip(this.transactionId)).toPromise();
      if (!res || !res.slip_url) {
        throw new Error('URL slip tidak valid.');
      }

      const fileName = `slip-pembayaran-${this.transactionId}-${Date.now()}.pdf`;
      await Filesystem.downloadFile({
        path: fileName,
        url: res.slip_url,
        directory: Directory.Documents,
      });
      
      loading.dismiss();
      
      // Tetap panggil kedua fungsi ini
      await this.showDownloadNotification(fileName); // Ini akan mengirim notifikasi jika diizinkan
      this.presentToast(`Slip berhasil diunduh!`, 'success'); // Ini akan selalu menampilkan pesan

    } catch (error: any) {
      loading.dismiss();
      // PERBAIKAN: Cek tipe error untuk menampilkan pesan yang lebih spesifik
      let errorMessage = 'Gagal mengunduh slip. Silakan coba lagi.';
      
      // Error jaringan biasanya tidak memiliki status atau statusnya 0
      if (error.status === 0 || error.name === 'HttpErrorResponse') {
        errorMessage = 'Gagal mengunduh slip. Periksa koneksi internet Anda.';
      } else if (error.message === 'URL slip tidak valid.') {
        errorMessage = 'Tidak dapat menemukan URL slip dari server.';
      }
      
      this.presentToast(errorMessage, 'danger');
    }
  }

  async showDownloadNotification(fileName: string) {
    const permissionStatus = await LocalNotifications.checkPermissions();
    if (permissionStatus.display === 'granted') {
      
      // PERBAIKAN: Buat ID unik yang aman untuk integer 32-bit
      const uniqueId = parseInt(Date.now().toString().substring(5));

      await LocalNotifications.schedule({
        notifications: [
          {
            title: "Unduhan Selesai",
            body: `File ${fileName} telah berhasil disimpan.`,
            id: uniqueId, // Gunakan ID yang sudah aman
            schedule: { at: new Date(Date.now() + 1000) },
            smallIcon: 'res://drawable/ic_stat_name', // Pastikan ikon ini ada
          }
        ]
      });
    }
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
