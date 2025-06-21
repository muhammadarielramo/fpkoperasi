import { Component, OnInit } from '@angular/core';
import { ToastController } from '@ionic/angular';
import { Subject } from 'rxjs';
import { debounceTime, distinctUntilChanged, switchMap } from 'rxjs/operators';
import { CollectorService } from 'src/app/services/collector.service';

@Component({
  standalone: false,
  selector: 'app-members',
  templateUrl: './members.page.html',
  styleUrls: ['./members.page.scss'],
})
export class MembersPage implements OnInit {
  public members: any[] = [];
  public isLoading: boolean = true;
  private searchSubject = new Subject<string>();

  constructor(
    private collectorService: CollectorService,
    private toastCtrl: ToastController
  ) {}

  ngOnInit() {
    // Gunakan RxJS untuk pencarian yang efisien
    this.searchSubject.pipe(
      debounceTime(500),
      distinctUntilChanged(),
      switchMap(searchTerm => this.collectorService.getCoachedMembers(searchTerm))
    ).subscribe({
      next: (observable) => {
        observable.subscribe({
          next: (res: any) => this.processMemberData(res), // Panggil fungsi pemroses
          error: (err: any) => this.handleError(err)
        });
      },
      error: (err: any) => this.handleError(err)
    });
  }

  ionViewWillEnter() {
    this.loadInitialData();
  }

  // Muat data awal atau saat pull-to-refresh
  loadInitialData(event?: any) {
    this.isLoading = true;
    this.searchSubject.next(''); // Trigger pencarian awal (tanpa kata kunci)
    if (event) {
      setTimeout(() => event.target.complete(), 1000);
    }
  }

  /**
   * Fungsi terpusat untuk memproses data member dan memperbaiki URL avatar.
   */
  private processMemberData(response: any) {
    const membersData = response.data || [];
    this.members = membersData.map((item: any) => {
      // Cek jika photo_url ada dan dimulai dengan http://
      if (item.photo_url && item.photo_url.startsWith('http://')) {
        // Ganti dengan https://
        item.photo_url = item.photo_url.replace('http://', 'https://');
      }
      return item;
    });
    this.isLoading = false;
  }

  // Dipanggil setiap kali ada input di search bar
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
