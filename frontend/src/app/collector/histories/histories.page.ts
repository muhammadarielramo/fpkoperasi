import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { ToastController } from '@ionic/angular';
import { CollectorService } from 'src/app/services/collector.service';

@Component({
  standalone: false,
  selector: 'app-histories',
  templateUrl: './histories.page.html',
  styleUrls: ['./histories.page.scss'],
})
export class HistoriesPage implements OnInit {
  dateRangeForm: FormGroup;
  transactions: any[] = [];
  totalDeposit: number = 0;
  isLoading: boolean = true;
  
  isDatePickerOpen = false;
  // Properti untuk menyimpan pilihan tanggal di modal
  tempStartDate: string = '';
  tempEndDate: string = '';

  constructor(
    private fb: FormBuilder,
    private collectorService: CollectorService,
    private toastCtrl: ToastController
  ) {
    const today = new Date();
    const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);

    this.dateRangeForm = this.fb.group({
      startDate: [this.formatDateToYMD(firstDayOfMonth)],
      endDate: [this.formatDateToYMD(today)]
    });
  }

  ngOnInit() {
    // Salin nilai awal ke properti sementara saat komponen dimuat
    const { startDate, endDate } = this.dateRangeForm.value;
    this.tempStartDate = startDate;
    this.tempEndDate = endDate;
  }

  ionViewWillEnter() {
    this.loadHistory();
  }

  private formatDateToYMD(date: Date): string {
    const year = date.getFullYear();
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const day = date.getDate().toString().padStart(2, '0');
    return `${year}-${month}-${day}`;
  }

  async loadHistory() {
    this.isLoading = true;
    const { startDate, endDate } = this.dateRangeForm.value;

    if (!startDate || !endDate) {
      this.presentToast('Harap pilih rentang tanggal yang valid.', 'warning');
      this.isLoading = false;
      return;
    }

    try {
      const response = await this.collectorService.getHistory(startDate, endDate);
      response.subscribe({
        next: (res: any) => {
          const transactionsData = res.data || [];
          
          // PERBAIKAN: Proses setiap transaksi untuk memperbaiki URL avatar
          this.transactions = transactionsData.map((trx: any) => {
            const photoUrl = trx.member?.user?.photo_url;
            if (photoUrl && photoUrl.startsWith('http://')) {
              trx.member.user.photo_url = photoUrl.replace('http://', 'https://');
            }
            return trx;
          });

          this.totalDeposit = this.transactions.reduce((sum: number, trx: any) => sum + parseFloat(trx.jumlah || 0), 0);
          this.isLoading = false;
        },
        error: (err) => {
          this.isLoading = false;
          this.presentToast('Gagal memuat riwayat transaksi.');
          console.error('Error loading history:', err);
        },
      });
    } catch (error) {
      this.isLoading = false;
      this.presentToast('Gagal memuat riwayat transaksi.');
      console.error('Error in loadHistory:', error);
    }
  }

  get formattedDateRange(): string {
    const { startDate, endDate } = this.dateRangeForm.value;
    if (!startDate || !endDate) return 'Pilih rentang tanggal';

    const options: Intl.DateTimeFormatOptions = { 
      day: 'numeric', 
      month: 'short', 
      year: 'numeric', 
      timeZone: 'UTC' 
    };
    
    // Gunakan UTC untuk konsistensi
    const start = new Date(startDate + 'T00:00:00Z');
    const end = new Date(endDate + 'T00:00:00Z');

    return `${start.toLocaleDateString('id-ID', options)} - ${end.toLocaleDateString('id-ID', options)}`;
  }

  // Dipanggil setiap kali pengguna mengubah tanggal di kalender
  onStartDateChange(event: any) {
    this.tempStartDate = event.detail.value.split('T')[0]; // Ambil hanya bagian tanggal
  }

  onEndDateChange(event: any) {
    this.tempEndDate = event.detail.value.split('T')[0]; // Ambil hanya bagian tanggal
  }

  // Dipanggil saat tombol "Selesai" di modal diklik
  confirmDateSelection() {
    if (!this.tempStartDate || !this.tempEndDate) {
      this.presentToast('Harap pilih tanggal mulai dan tanggal akhir.', 'warning');
      return;
    }

    // Validasi bahwa tanggal mulai tidak lebih besar dari tanggal akhir
    const startDate = new Date(this.tempStartDate);
    const endDate = new Date(this.tempEndDate);
    
    if (startDate > endDate) {
      this.presentToast('Tanggal mulai tidak boleh lebih besar dari tanggal akhir.', 'warning');
      return;
    }

    // Update form dengan tanggal yang dipilih
    this.dateRangeForm.patchValue({
      startDate: this.tempStartDate,
      endDate: this.tempEndDate
    });
    
    this.isDatePickerOpen = false;
    this.loadHistory(); // Reload data dengan tanggal baru
  }

  // Dipanggil saat tombol "Batal" di modal diklik
  cancelDateSelection() {
    // Kembalikan pilihan sementara ke nilai form yang asli
    const { startDate, endDate } = this.dateRangeForm.value;
    this.tempStartDate = startDate;
    this.tempEndDate = endDate;
    this.isDatePickerOpen = false;
  }

  openDatePicker() {
    // Pastikan pilihan sementara sinkron dengan nilai form saat membuka
    const { startDate, endDate } = this.dateRangeForm.value;
    this.tempStartDate = startDate;
    this.tempEndDate = endDate;
    this.isDatePickerOpen = true;
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