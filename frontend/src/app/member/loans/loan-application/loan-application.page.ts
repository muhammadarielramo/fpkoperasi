import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';


@Component({
  standalone: false,
  selector: 'app-loan-application',
  templateUrl: './loan-application.page.html',
  styleUrls: ['./loan-application.page.scss'],
})
export class LoanApplicationPage implements OnInit {
  showDatePicker = false;
  tanggalPengajuan: string = '';
  showLoanSuccess = false;

  constructor(private router: Router) { }

  ngOnInit() {
  }

  openDatePicker() {
    this.showDatePicker = true;
  }

  setTanggalPengajuan(event: any) {
    const selectedDate = new Date(event.detail.value);
    const formatted = selectedDate.toLocaleDateString('id-ID'); // misal: 23/05/2025
    this.tanggalPengajuan = formatted;
    this.showDatePicker = false;
  }

    // Method untuk menampilkan modal setelah pengajuan berhasil
  onLoanSubmissionSuccess() {
    this.showLoanSuccess = true;
    
    // Redirect setelah 2 detik
    setTimeout(() => {
      this.showLoanSuccess = false;
      this.router.navigate(['/member/loans']);
    }, 2000);
  }

}
