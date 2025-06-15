import { Component, OnInit } from '@angular/core';

@Component({
  standalone: false,
  selector: 'app-loan-application',
  templateUrl: './loan-application.page.html',
  styleUrls: ['./loan-application.page.scss'],
})
export class LoanApplicationPage implements OnInit {
  showDatePicker = false;
  tanggalPengajuan: string = '';

  constructor() { }

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

}
