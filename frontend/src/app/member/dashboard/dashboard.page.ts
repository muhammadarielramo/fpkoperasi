import { Component, OnInit } from '@angular/core';
import { ToastController } from '@ionic/angular';
import { MemberService } from 'src/app/services/member.service';
import { register } from 'swiper/element/bundle';

register();

@Component({
  standalone: false,
  selector: 'app-dashboard',
  templateUrl: './dashboard.page.html',
  styleUrls: ['./dashboard.page.scss'],
})
export class DashboardPage implements OnInit {
  // Properti untuk menyimpan data langsung dari API
  public dashboardData: any = {
    simpanan_wajib: 0,
    simpanan_pokok: 0,
    simpanan_sukarela: 0,
    total_pinjaman: 0,
  };
  public isLoading: boolean = true;

  constructor(
    private memberService: MemberService,
    private toastCtrl: ToastController
  ) {}

  ngOnInit() {}

  // Gunakan ionViewWillEnter agar data selalu segar
  ionViewWillEnter() {
    this.loadDashboardData();
  }

  async loadDashboardData(event?: any) {
    this.isLoading = true;
    // Memanggil satu metode yang efisien untuk mendapatkan semua data dashboard
    (await this.memberService.getDashboardData()).subscribe({
      next: (res: any) => {
        if (res && res.data) {
          // Langsung gunakan data dari API tanpa perlu perhitungan manual
          this.dashboardData = res.data;
        }
        this.isLoading = false;
        if (event) event.target.complete();
      },
      error: (err) => {
        this.isLoading = false;
        if (event) event.target.complete();
        this.presentErrorToast('Gagal memuat data dashboard.');
      },
    });
  }

  async presentErrorToast(message: string) {
    const toast = await this.toastCtrl.create({
      message: message,
      duration: 3000,
      position: 'top',
      color: 'danger',
    });
    await toast.present();
  }
}
