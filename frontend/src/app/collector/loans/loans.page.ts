import { Component, OnInit } from '@angular/core';
import { LoadingController, ToastController } from '@ionic/angular';
import { CollectorService } from 'src/app/services/collector.service';

@Component({
  standalone: false,
  selector: 'app-loans',
  templateUrl: './loans.page.html',
  styleUrls: ['./loans.page.scss'],
})
export class LoansPage implements OnInit {
  public allLoans: any[] = []; // Menyimpan data asli dari API
  public filteredLoans: any[] = []; // Menyimpan data yang akan ditampilkan
  public isLoading: boolean = true;

  constructor(
    private collectorService: CollectorService,
    private loadingCtrl: LoadingController,
    private toastCtrl: ToastController
  ) {}

  ngOnInit() {}

  // Gunakan ionViewWillEnter agar data di-refresh setiap kali halaman ini ditampilkan
  ionViewWillEnter() {
    this.loadMemberLoans();
  }

  async loadMemberLoans(event?: any) {
    // Tampilkan loading spinner hanya saat pertama kali memuat
    if (!event) {
      this.isLoading = true;
    }

    (await this.collectorService.getMemberLoans()).subscribe({
      next: (res) => {
        if (res && res.data) {
          this.allLoans = res.data;
          this.filteredLoans = res.data; // Awalnya, tampilkan semua
        }
        this.isLoading = false;
        if (event) event.target.complete(); // Selesaikan event refresher
      },
      error: (err) => {
        this.isLoading = false;
        if (event) event.target.complete();
        this.presentToast('Gagal memuat data pinjaman.');
        console.error(err);
      },
    });
  }

  handleSearch(event: any) {
    const searchTerm = event.target.value.toLowerCase();

    if (!searchTerm) {
      this.filteredLoans = this.allLoans; // Jika search kosong, tampilkan semua
      return;
    }

    // Filter data berdasarkan nama anggota
    this.filteredLoans = this.allLoans.filter((loan) => {
      // Pastikan path ke nama pengguna benar
      const memberName = loan.member?.user?.name.toLowerCase();
      return memberName && memberName.includes(searchTerm);
    });
  }

  async presentToast(message: string) {
    const toast = await this.toastCtrl.create({
      message: message,
      duration: 3000,
      position: 'top',
      color: 'danger',
    });
    await toast.present();
  }
}
