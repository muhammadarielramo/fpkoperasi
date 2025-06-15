import { Component, OnInit } from '@angular/core';

@Component({
  standalone: false,
  selector: 'app-histories',
  templateUrl: './histories.page.html',
  styleUrls: ['./histories.page.scss'],
})
export class HistoriesPage implements OnInit {
  selectedFilter: string = 'semua';

  constructor() { }

  ngOnInit() {
  }

  filterChanged(event: any) {
    this.selectedFilter = event.detail.value;
    console.log('Filter aktif:', this.selectedFilter);
    // Sesuaikan logika filter data transaksi di sini
  }

}
