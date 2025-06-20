import { Location } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ToastController, LoadingController } from '@ionic/angular';
import { CollectorService } from 'src/app/services/collector.service';
import { DepositService } from 'src/app/services/deposit.service';

@Component({
  standalone: false,
  selector: 'app-deposit-savings',
  templateUrl: './deposit-savings.page.html',
  styleUrls: ['./deposit-savings.page.scss'],
})
export class DepositSavingsPage implements OnInit {
  depositForm: FormGroup;
  memberId!: number;
  memberName: string = 'Memuat...';

  showDepositSuccess = false;
  showDatePicker = false;
  
  constructor(
    private location: Location,
    private router: Router,
    private route: ActivatedRoute,
    private formBuilder: FormBuilder,
    private collectorService: CollectorService,
    private depositService: DepositService,
    private toastCtrl: ToastController,
    private loadingCtrl: LoadingController
  ) {
    this.depositForm = this.formBuilder.group({
      tgl_simpanan: ['', Validators.required],
      nominal: ['', [Validators.required, Validators.min(1000)]],
      jenis_simpanan: ['pokok', Validators.required],
    });
  }

  ngOnInit() {
    const id = this.route.snapshot.paramMap.get('id');
    if (id) {
      this.memberId = +id;
      this.loadMemberDetails();
    }
  }

  async loadMemberDetails() {
    (await this.collectorService.getMemberDetails(this.memberId)).subscribe({
      next: (res: any) => {
        if (res && res.data) {
          this.memberName = res.data.name;
        }
      },
      error: () => {
        this.memberName = 'Gagal memuat nama';
        this.presentToast('Gagal memuat detail anggota.');
      },
    });
  }

  goBack() {
    this.location.back();
  }

  openDatePicker() {
    this.showDatePicker = true;
  }

  setDateValue(event: any) {
    const date = new Date(event.detail.value);
    const formattedDate = date.toISOString().split('T')[0];
    this.depositForm.patchValue({ tgl_simpanan: formattedDate });
    this.showDatePicker = false;
  }

  private getCurrentLocation(): Promise<GeolocationPosition> {
    return new Promise((resolve, reject) => {
      if (!navigator.geolocation) {
        reject(new Error('Geolocation tidak didukung oleh perangkat ini.'));
        return;
      }
      navigator.geolocation.getCurrentPosition(resolve, reject, {
        enableHighAccuracy: true,
        timeout: 15000,
        maximumAge: 0
      });
    });
  }

  async submitDeposit() {
    if (this.depositForm.invalid) {
      this.presentToast('Harap lengkapi semua data dengan benar.', 'warning');
      return;
    }

    const loading = await this.loadingCtrl.create({ message: 'Memverifikasi lokasi...' });
    await loading.present();

    try {
      // Langkah 1: Coba dapatkan lokasi
      const position = await this.getCurrentLocation();
      
      // Jika berhasil, ubah pesan loading dan lanjutkan
      loading.message = 'Menyimpan data...';
      
      const payload = {
        ...this.depositForm.value,
        id_member: this.memberId,
        latitude: position.coords.latitude,
        longitude: position.coords.longitude,
        accuracy: position.coords.accuracy
      };

      (await this.depositService.saveDeposit(this.memberId, payload)).subscribe({
        next: (res: any) => {
          loading.dismiss();
          if (res && res.success === true) {
            this.showDepositSuccess = true;
            setTimeout(() => {
              this.showDepositSuccess = false;
              this.router.navigate(['/collector/savings']);
            }, 2000);
          } else {
            const errorMessage = res.message || 'Terjadi kesalahan pada server.';
            this.presentToast(errorMessage);
          }
        },
        error: (err) => {
          loading.dismiss();
          const message = err.error?.message || 'Gagal menyimpan setoran.';
          this.presentToast(message);
        },
      });

    } catch (locationError: any) {
      // PERBAIKAN: Tangkap error jika gagal mendapatkan lokasi
      await loading.dismiss();
      // Berikan pesan yang lebih spesifik
      let message = 'Gagal mendapatkan lokasi. Pastikan GPS aktif dan izin lokasi telah diberikan untuk aplikasi ini.';
      if (locationError.message) {
          message = locationError.message;
      }
      this.presentToast(message);
    }
  }
  
  async presentToast(message: string, color: string = 'danger') {
    const toast = await this.toastCtrl.create({
      message,
      duration: 3500,
      position: 'top',
      color: color,
    });
    await toast.present();
  }
}
