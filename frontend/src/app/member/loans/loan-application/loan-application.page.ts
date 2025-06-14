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
  selectedDate: string = '';
  showLoanSuccess = false;

  constructor(private router: Router) { }

  ngOnInit() {
  }

  openDatePicker() {
    this.showDatePicker = true;
  }

  setDateValue(event: any) {
    const selectedDate = event.detail.value;
    if (selectedDate) {
      // Format the date to DD/MM/YYYY
      const date = new Date(selectedDate);
      const day = String(date.getDate()).padStart(2, '0');
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const year = date.getFullYear();
      
      this.selectedDate = `${day}/${month}/${year}`;
    }
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
