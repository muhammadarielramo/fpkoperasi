import { Location } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ToastController, LoadingController } from '@ionic/angular';
import { CollectorService } from 'src/app/services/collector.service';
import { DepositService } from 'src/app/services/deposit.service';
import { Geolocation, Position } from '@capacitor/geolocation';

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
  
  private lastPosition: Position | null = null;

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
      jenis_simpanan: ['wajib', Validators.required],
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

  private async getCurrentLocation(): Promise<Position> {
    const permissions = await Geolocation.checkPermissions();
    if (permissions.location !== 'granted') {
      const request = await Geolocation.requestPermissions();
      if (request.location !== 'granted') {
        throw new Error('Izin lokasi ditolak. Aktifkan di pengaturan perangkat.');
      }
    }
    return Geolocation.getCurrentPosition({
      enableHighAccuracy: true,
      timeout: 15000,
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
      const position = await this.getCurrentLocation();
      
      // PERBAIKAN: Cast 'position' ke 'any' untuk mengakses properti 'mocked'
      if ((position as any).mocked) {
        throw new Error('Terdeteksi penggunaan lokasi palsu (Fake GPS).');
      }

      if (position.coords.accuracy > 500) {
        throw new Error('Akurasi GPS terlalu rendah. Pastikan sinyal baik.');
      }

      const locationAge = Date.now() - position.timestamp;
      if (locationAge > 60000) {
        throw new Error('Data lokasi tidak real-time. Mohon refresh GPS Anda.');
      }

      if (this.lastPosition) {
        const distance = this.calculateDistance(this.lastPosition.coords, position.coords);
        const timeDiffSeconds = (position.timestamp - this.lastPosition.timestamp) / 1000;
        if (timeDiffSeconds > 1) { // Hanya cek jika selisih waktu lebih dari 1 detik
          const speedKph = (distance / timeDiffSeconds) * 3600;
          if (speedKph > 150) { // Kecepatan > 150 km/jam
            throw new Error('Pergerakan lokasi tidak wajar terdeteksi.');
          }
        }
      }

      this.lastPosition = position;
      
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
          if (res && res.success) {
            this.showDepositSuccess = true;
            setTimeout(() => {
                this.showDepositSuccess = false;
                this.router.navigate(['/collector/savings']);
            }, 2000);
          } else {
             this.presentToast(res.message || 'Gagal menyimpan setoran.', 'danger');
          }
        },
        error: (err) => {
          loading.dismiss();
          this.presentToast(err.error?.message || 'Gagal menyimpan setoran.', 'danger');
        },
      });

    } catch (error: any) {
      await loading.dismiss();
      this.presentToast(error.message || 'Gagal mendapatkan lokasi.', 'danger');
    }
  }

  private calculateDistance(coords1: any, coords2: any): number {
      const R = 6371; // Radius bumi dalam km
      const dLat = (coords2.latitude - coords1.latitude) * Math.PI / 180;
      const dLon = (coords2.longitude - coords1.longitude) * Math.PI / 180;
      const a = 
          0.5 - Math.cos(dLat)/2 + 
          Math.cos(coords1.latitude * Math.PI / 180) * Math.cos(coords2.latitude * Math.PI / 180) * (1 - Math.cos(dLon)) / 2;
      return R * 2 * Math.asin(Math.sqrt(a));
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
