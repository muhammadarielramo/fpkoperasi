import { Location } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ToastController, LoadingController } from '@ionic/angular';
import { LoanService } from 'src/app/services/loan.service';
import { InstallmentService } from 'src/app/services/installment.service';
import { Geolocation, Position } from '@capacitor/geolocation'; // 1. Impor dari Capacitor

@Component({
  standalone: false,
  selector: 'app-payment-entry',
  templateUrl: './payment-entry.page.html',
  styleUrls: ['./payment-entry.page.scss'],
})
export class PaymentEntryPage implements OnInit {
  paymentForm: FormGroup;
  loanId!: number;
  memberName: string = 'Memuat...';
  
  showPaymentSuccess = false;
  showDatePicker = false;
  showLocationModal = false;

  constructor(
    private location: Location,
    private router: Router,
    private route: ActivatedRoute,
    private formBuilder: FormBuilder,
    private loanService: LoanService,
    private installmentService: InstallmentService,
    private toastCtrl: ToastController,
    private loadingCtrl: LoadingController
  ) {
    this.paymentForm = this.formBuilder.group({
      besar_ciclan: ['', [Validators.required, Validators.min(1)]],
      angsuran_ke: ['', [Validators.required, Validators.min(1)]],
      tgl_pembayaran: [new Date().toISOString().split('T')[0], Validators.required],
      status: ['lunas', Validators.required],
    });
  }

  ngOnInit() {
    const id = this.route.snapshot.paramMap.get('id');
    if (id) {
      this.loanId = +id;
      this.loadPaymentInfo();
    }
  }

  async loadPaymentInfo() {
    (await this.loanService.getLoanPaymentInfo(this.loanId)).subscribe({
      next: (res: any) => {
        if (res && res.data) {
          this.memberName = res.data.member;
        }
      },
      error: (err: any) => {
        const message = err.error?.message || 'Gagal memuat info pembayaran.';
        this.presentToast(message);
        this.router.navigate(['/collector/loans']);
      },
    });
  }

  async submitPayment() {
    if (this.paymentForm.invalid) {
      this.presentToast('Harap lengkapi semua data pembayaran.', 'warning');
      return;
    }
    // Langsung minta lokasi dan verifikasi
    this.requestLocation();
  }

  private async proceedWithSubmission(position: Position) {
    const loading = await this.loadingCtrl.create({ message: 'Menyimpan pembayaran...' });
    await loading.present();

    const payload = {
      ...this.paymentForm.value,
      latitude: position.coords.latitude,
      longitude: position.coords.longitude,
      accuracy: position.coords.accuracy
    };

    (await this.installmentService.submitInstallment(this.loanId, payload)).subscribe({
      next: () => {
        loading.dismiss();
        this.showPaymentSuccess = true;
        setTimeout(() => {
          this.showPaymentSuccess = false;
          this.router.navigate(['/collector/loans']);
        }, 2000);
      },
      error: (err: any) => {
        loading.dismiss();
        const message = err.error?.message || 'Gagal menyimpan pembayaran.';
        this.presentToast(message);
      },
    });
  }
  
  // --- PERBAIKAN: Menggabungkan logika izin dan pengambilan lokasi ---
  private async requestLocation() {
    const loading = await this.loadingCtrl.create({ message: 'Memverifikasi lokasi...' });
    await loading.present();
    try {
      // 1. Minta izin menggunakan Capacitor Geolocation
      const permissions = await Geolocation.checkPermissions();
      if (permissions.location !== 'granted') {
        const request = await Geolocation.requestPermissions();
        if (request.location !== 'granted') {
          throw new Error('Izin lokasi ditolak.');
        }
      }

      // 2. Ambil posisi menggunakan Capacitor Geolocation
      const position = await Geolocation.getCurrentPosition({
        enableHighAccuracy: true,
        timeout: 15000,
      });

      // 3. Cek lokasi palsu (mocked)
      if ((position as any).mocked) {
        throw new Error('Terdeteksi penggunaan lokasi palsu (Fake GPS).');
      }

      // 4. Cek akurasi lokasi
      if (position.coords.accuracy > 500) {
        throw new Error('Akurasi GPS terlalu rendah. Pastikan sinyal baik.');
      }

      await loading.dismiss();
      this.proceedWithSubmission(position); // Lanjutkan jika semua pengecekan lolos

    } catch (error: any) {
      await loading.dismiss();
      this.presentToast(error.message || 'Gagal mendapatkan lokasi. Pastikan GPS aktif.', 'danger');
    }
  }

  // Fungsi lain tetap sama
  goBack() { this.location.back(); }
  openDatePicker() { this.showDatePicker = true; }
  setDateValue(event: any) {
    const date = new Date(event.detail.value);
    const formattedDate = date.toISOString().split('T')[0];
    this.paymentForm.patchValue({ tgl_pembayaran: formattedDate });
    this.showDatePicker = false;
  }
  async presentToast(message: string, color: string = 'danger') {
    const toast = await this.toastCtrl.create({
      message,
      duration: 3500,
      position: 'top',
      color,
    });
    await toast.present();
  }
}
