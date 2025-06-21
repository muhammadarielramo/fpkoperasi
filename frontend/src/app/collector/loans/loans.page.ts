import { Component, OnInit } from '@angular/core';
import { ToastController } from '@ionic/angular';
import { CollectorService } from 'src/app/services/collector.service';
import { Subject } from 'rxjs';
import { debounceTime, distinctUntilChanged, switchMap } from 'rxjs/operators';

@Component({
  standalone: false,
  selector: 'app-loans',
  templateUrl: './loans.page.html',
  styleUrls: ['./loans.page.scss'],
})
export class LoansPage implements OnInit {
  public allLoans: any[] = [];
  public filteredLoans: any[] = [];
  public isLoading: boolean = true;
  private searchSubject = new Subject<string>();

  constructor(
    private collectorService: CollectorService,
    private toastCtrl: ToastController
  ) {}

  ngOnInit() {
    this.searchSubject.pipe(
      debounceTime(500),
      distinctUntilChanged(),
      switchMap(searchTerm => this.collectorService.getCoachedMembers(searchTerm))
    ).subscribe({
      next: (observable) => {
        observable.subscribe({
          next: (res: any) => {
            // Asumsi getCoachedMembers yang dipakai, bukan getMemberLoans
            this.allLoans = res.data || [];
            this.filteredLoans = res.data || [];
            this.isLoading = false;
          },
          error: (err: any) => this.handleError(err)
        });
      },
      error: (err: any) => this.handleError(err)
    });
  }

  ionViewWillEnter() {
    this.loadMemberLoans();
  }

  async loadMemberLoans(event?: any) {
    this.isLoading = true;
    (await this.collectorService.getMemberLoans()).subscribe({
      next: (res: any) => {
        if (res && Array.isArray(res.data)) {
          // PERBAIKAN: Urutkan data berdasarkan ID (atau tanggal pembuatan jika ada)
          // Asumsi ID yang lebih besar berarti lebih baru
          const sortedData = res.data.sort((a: any, b: any) => b.id - a.id);
          this.allLoans = sortedData;
          this.filteredLoans = sortedData;
        } else {
          this.allLoans = [];
          this.filteredLoans = [];
        }
        this.isLoading = false;
        if (event) event.target.complete();
      },
      error: (err: any) => this.handleError(err, event)
    });
  }

  handleSearch(event: any) {
    const searchTerm = event.target.value.toLowerCase();
    if (!searchTerm) {
      this.filteredLoans = [...this.allLoans];
      return;
    }
    this.filteredLoans = this.allLoans.filter(loan => 
      loan.member?.user?.name.toLowerCase().includes(searchTerm)
    );
  }

  private handleError(error: any, event?: any) {
    this.isLoading = false;
    if (event) event.target.complete();
    this.presentToast('Gagal memuat data pinjaman.');
    console.error(error);
  }

  async presentToast(message: string) {
    const toast = await this.toastCtrl.create({
      message,
      duration: 3000,
      position: 'top',
      color: 'danger',
    });
    await toast.present();
  }
}
