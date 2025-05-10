import { Component, OnInit } from '@angular/core';

@Component({
  standalone: false,
  selector: 'app-dashboard',
  templateUrl: './dashboard.page.html',
  styleUrls: ['./dashboard.page.scss'],
})
export class DashboardPage implements OnInit {
  menuItems = [
    { label: 'Riwayat Transaksi', icon: 'assets/icons/history.png' },
    { label: 'Pengajuan Pinjaman', icon: 'assets/icons/loan.png' },
    { label: 'Slip Pembayaran', icon: 'assets/icons/slip.png' },
    { label: 'Status Pinjaman', icon: 'assets/icons/status.png' },
    { label: 'Info & Pengingat Transaksi Anda', icon: 'assets/icons/info.png' },
  ];

  constructor() { }

  ngOnInit() {
  }

}
