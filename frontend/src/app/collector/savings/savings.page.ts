import { Component, OnInit } from '@angular/core';
import { LoadingController, ToastController } from '@ionic/angular';
import { CollectorService } from 'src/app/services/collector.service';
import { debounceTime, distinctUntilChanged, switchMap } from 'rxjs/operators';
import { Subject } from 'rxjs';

@Component({
  standalone: false,
  selector: 'app-savings',
  templateUrl: './savings.page.html',
  styleUrls: ['./savings.page.scss'],
})
export class SavingsPage implements OnInit {
  public members: any[] = [];
  public isLoading: boolean = true;
  private searchSubject = new Subject<string>();

  constructor(
    private collectorService: CollectorService,
    private toastCtrl: ToastController
  ) {}

  ngOnInit() {
    this.searchSubject.pipe(
      debounceTime(500), // Tunggu 500ms setelah user berhenti mengetik
      distinctUntilChanged(), // Hanya kirim request jika kata kunci berubah
      switchMap(searchTerm => this.collectorService.getCoachedMembers(searchTerm))
    ).subscribe({
      next: (observable) => {
        observable.subscribe({
          next: res => {
            this.members = res.data || [];
            this.isLoading = false;
          },
          error: err => this.handleError(err)
        });
      },
      error: err => this.handleError(err)
    });
  }

  ionViewWillEnter() {
    this.loadInitialData();
  }

  loadInitialData(event?: any) {
    this.isLoading = true;
    this.searchSubject.next(''); // Trigger pencarian awal (kosong)
    if (event) {
      setTimeout(() => event.target.complete(), 1000);
    }
  }

  handleSearch(event: any) {
    const searchTerm = event.target.value.toLowerCase();
    this.isLoading = true;
    this.searchSubject.next(searchTerm);
  }

  private handleError(error: any) {
    this.isLoading = false;
    this.presentToast('Gagal memuat data anggota.');
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
