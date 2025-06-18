import { Component, OnInit } from '@angular/core';
import { ToastController } from '@ionic/angular';
import { LoanService } from 'src/app/services/loan.service';

@Component({
  standalone: false,
  selector: 'app-loans',
  templateUrl: './loans.page.html',
  styleUrls: ['./loans.page.scss'],
})
export class LoansPage implements OnInit {
  public loans: any[] = [];
  public isLoading: boolean = true;

  constructor(
    private loanService: LoanService,
    private toastCtrl: ToastController
  ) {}

  ngOnInit() {}

  // Gunakan ionViewWillEnter agar data selalu di-refresh
  ionViewWillEnter() {
    this.loadLoanHistory();
  }

  async loadLoanHistory(event?: any) {
    this.isLoading = true;
    (await this.loanService.getMyLoans()).subscribe({
      next: (res: any) => {
        if (res && Array.isArray(res.data)) {
          // Urutkan data agar yang terbaru di atas
          this.loans = res.data.sort((a: any, b: any) => 
            new Date(b.created_at).getTime() - new Date(a.created_at).getTime()
          );
        } else {
          this.loans = [];
        }
        this.isLoading = false;
        if (event) event.target.complete();
      },
      error: (err) => {
        this.isLoading = false;
        if (event) event.target.complete();
        this.presentToast('Gagal memuat riwayat pinjaman.');
        console.error(err);
      },
    });
  }

  // Fungsi untuk menangani pull-to-refresh
  handleRefresh(event: any) {
    this.loadLoanHistory(event);
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
