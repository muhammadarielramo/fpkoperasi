import { Location } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { LoadingController, ToastController } from '@ionic/angular';
import { LoanService } from 'src/app/services/loan.service';

@Component({
  standalone: false,
  selector: 'app-loan-application',
  templateUrl: './loan-application.page.html',
  styleUrls: ['./loan-application.page.scss'],
})
export class LoanApplicationPage implements OnInit {
  loanForm: FormGroup;
  showDatePicker = false;
  showLoanSuccess = false;

  constructor(
    private router: Router,
    private location: Location,
    private formBuilder: FormBuilder,
    private loanService: LoanService,
    private loadingCtrl: LoadingController,
    private toastCtrl: ToastController
  ) {
    // Inisialisasi form dengan validasi yang diperlukan
    this.loanForm = this.formBuilder.group({
      jumlah_pinjaman: ['', Validators.required],
      tgl_pengajuan: [this.getTodayDate(), Validators.required],
      tenor: ['', Validators.required],
    });
  }

  ngOnInit() {}

  // Helper untuk mendapatkan tanggal hari ini dalam format YYYY-MM-DD
  getTodayDate(): string {
    return new Date().toISOString().split('T')[0];
  }

  goBack() {
    this.location.back();
  }

  openDatePicker() {
    this.showDatePicker = true;
  }

  setDateValue(event: any) {
    const date = new Date(event.detail.value);
    const formattedDate = date.toISOString().split('T')[0];
    // Perbarui nilai form kontrol secara reaktif
    this.loanForm.patchValue({ tgl_pengajuan: formattedDate });
    this.showDatePicker = false;
  }

  async submitApplication() {
    if (this.loanForm.invalid) {
      this.presentToast('Harap lengkapi semua kolom pengajuan.', 'warning');
      return;
    }

    const loading = await this.loadingCtrl.create({
      message: 'Mengirim pengajuan...',
    });
    await loading.present();

    (await this.loanService.submitApplication(this.loanForm.value)).subscribe({
      next: (res: any) => {
        loading.dismiss();
        this.showLoanSuccess = true;
        setTimeout(() => {
          this.showLoanSuccess = false;
          // Arahkan kembali ke halaman daftar pinjaman
          this.router.navigate(['/member/loans']);
        }, 2000);
      },
      error: (err: any) => {
        loading.dismiss();
        const message = err.error?.message || 'Gagal mengajukan pinjaman. Coba lagi.';
        this.presentToast(message, 'danger');
        console.error(err);
      },
    });
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
}
