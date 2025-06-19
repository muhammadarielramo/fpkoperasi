import { Location } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ToastController } from '@ionic/angular';
import { LoanService } from 'src/app/services/loan.service';

@Component({
  standalone: false,
  selector: 'app-loan-details',
  templateUrl: './loan-details.page.html',
  styleUrls: ['./loan-details.page.scss'],
})
export class LoanDetailsPage implements OnInit {
  public loanDetail: any = null;
  public isLoading: boolean = true;

  constructor(
    private location: Location,
    private route: ActivatedRoute,
    private loanService: LoanService,
    private toastCtrl: ToastController
  ) {}

  ngOnInit() {
    // Ambil ID pinjaman dari parameter URL
    const loanId = this.route.snapshot.paramMap.get('id');
    if (loanId) {
      this.loadLoanDetails(+loanId);
    } else {
      this.isLoading = false;
      this.presentToast('ID Pinjaman tidak ditemukan.');
    }
  }

  async loadLoanDetails(id: number) {
    this.isLoading = true;
    (await this.loanService.getLoanInfo(id)).subscribe({
      next: (res: any) => {
        if (res && res.data) {
          this.loanDetail = res.data;
        } else {
          this.presentToast('Data detail pinjaman tidak ditemukan.');
        }
        this.isLoading = false;
      },
      error: (err) => {
        this.isLoading = false;
        this.presentToast('Gagal memuat detail pinjaman.');
        console.error(err);
      },
    });
  }

  goBack() {
    this.location.back();
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
