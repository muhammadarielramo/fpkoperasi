import { Component, OnInit } from '@angular/core';
import { ToastController } from '@ionic/angular';
import { TransactionService } from 'src/app/services/transaction.service';
import { forkJoin, of } from 'rxjs';
import { catchError } from 'rxjs/operators';

@Component({
  standalone: false,
  selector: 'app-histories',
  templateUrl: './histories.page.html',
  styleUrls: ['./histories.page.scss'],
})
export class HistoriesPage implements OnInit {
  public selectedFilter: string = 'semua';
  public transactions: any[] = [];
  public isLoading: boolean = true;

  constructor(
    private transactionService: TransactionService,
    private toastCtrl: ToastController
  ) {}

  ngOnInit() {}

  ionViewWillEnter() {
    this.loadHistory();
  }

  async loadHistory(event?: any) {
    this.isLoading = true;
    
    let typesToFetch: string[] = [];

    if (this.selectedFilter === 'semua') {
      typesToFetch = ['deposit', 'installment', 'loan'];
    } else if (this.selectedFilter === 'pinjaman') {
      typesToFetch = ['installment', 'loan'];
    } else { // Untuk 'deposit'
      typesToFetch = ['deposit'];
    }

    try {
      const observablePromises = typesToFetch.map(type => 
        this.transactionService.getHistory(type)
      );
      
      const observables = await Promise.all(observablePromises);

      forkJoin(
        observables.map(obs => obs.pipe(catchError(() => of({ data: {} })))) // Harapkan objek
      ).subscribe((results: any[]) => {
          // PERBAIKAN: Ubah objek menjadi array sebelum digabungkan
          this.transactions = results
            .reduce((acc: any[], res: any) => {
              // Cek jika data adalah objek dan bukan null
              if (res && res.data && typeof res.data === 'object' && res.data !== null) {
                // Ambil semua nilai dari objek dan tambahkan ke akumulator
                return acc.concat(Object.values(res.data));
              }
              return acc;
            }, [])
            .sort((a: any, b: any) => new Date(b.tgl_transaksi).getTime() - new Date(a.tgl_transaksi).getTime());
          
          this.isLoading = false;
          if (event) event.target.complete();
        });
    } catch (err) {
      this.isLoading = false;
      if (event) event.target.complete();
      this.presentToast('Gagal memuat riwayat transaksi.');
    }
  }

  filterChanged(event: any) {
    this.selectedFilter = event.detail.value;
    this.loadHistory();
  }

  getTransactionTitle(transaction: any): string {
    if (transaction.id_deposit) return `Simpanan`;
    if (transaction.id_installment) return 'Pembayaran Pinjaman';
    if (transaction.id_loan) return 'Pencairan Pinjaman';
    return 'Transaksi';
  }

  handleRefresh(event: any) {
    this.loadHistory(event);
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
