import { Component, OnInit, OnDestroy } from '@angular/core';
import { ToastController } from '@ionic/angular';
import { Subject, Subscription, from, of } from 'rxjs';
import { debounceTime, distinctUntilChanged, switchMap, catchError } from 'rxjs/operators';
import { CollectorService } from 'src/app/services/collector.service';

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
  private searchSubscription: Subscription | undefined;

  constructor(
    private collectorService: CollectorService,
    private toastCtrl: ToastController
  ) {}

  ngOnInit() {
    // Subscription ini sekarang HANYA untuk menangani input dari search bar.
    this.searchSubscription = this.searchSubject.pipe(
      debounceTime(500),
      distinctUntilChanged(),
      switchMap(searchTerm => 
        from(this.collectorService.getCoachedMembers(searchTerm)).pipe(
          switchMap(obs => obs),
          catchError(err => {
            this.handleError(err);
            return of({ data: [] }); // Kembalikan data kosong jika ada error
          })
        )
      )
    ).subscribe((res: any) => {
      this.processMemberData(res);
    });
  }

  ngOnDestroy() {
    // Selalu batalkan subscription untuk mencegah kebocoran memori
    if (this.searchSubscription) {
      this.searchSubscription.unsubscribe();
    }
  }

  ionViewWillEnter() {
    // Panggil metode ini untuk memuat data setiap kali halaman ditampilkan.
    this.loadInitialData();
  }

  async loadInitialData(event?: any) {
    this.isLoading = true;
    try {
      const observable = await this.collectorService.getCoachedMembers(); // Tanpa kata kunci
      observable.subscribe({
        next: (res: any) => {
          this.processMemberData(res);
          if (event) event.target.complete();
        },
        error: (err: any) => {
          this.handleError(err);
          if (event) event.target.complete();
        }
      });
    } catch (err) {
      this.handleError(err);
      if (event) event.target.complete();
    }
  }

  handleSearch(event: any) {
    const searchTerm = event.target.value.toLowerCase();
    // Jika kolom pencarian kosong, muat ulang semua data.
    if (!searchTerm) {
      this.loadInitialData();
      return;
    }
    this.isLoading = true;
    this.searchSubject.next(searchTerm);
  }

  private processMemberData(response: any) {
    const membersData = response.data || [];
    this.members = membersData.map((item: any) => {
      if (item.photo_url && item.photo_url.startsWith('http://')) {
        item.photo_url = item.photo_url.replace('http://', 'https://');
      }
      return item;
    });
    this.isLoading = false;
  }

  private handleError(error: any) {
    this.isLoading = false;
    this.members = [];
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
