import { Location } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ToastController, LoadingController } from '@ionic/angular';
import { LoanService } from 'src/app/services/loan.service';
import { InstallmentService } from 'src/app/services/installment.service';

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
          // Anda bisa mengisi field lain di sini jika API mengembalikannya
          // Contoh: this.paymentForm.patchValue({ angsuran_ke: res.data.next_installment });
        }
      },
      error: () => this.presentToast('Gagal memuat info pembayaran.'),
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
    this.paymentForm.patchValue({ tgl_pembayaran: formattedDate });
    this.showDatePicker = false;
  }

  async submitPayment() {
    if (this.paymentForm.invalid) {
      this.presentToast('Harap lengkapi semua data pembayaran.', 'warning');
      return;
    }
    // Cek izin lokasi terlebih dahulu
    this.checkLocationPermission();
  }

  private async proceedWithSubmission(position: GeolocationPosition) {
    const loading = await this.loadingCtrl.create({ message: 'Menyimpan pembayaran...' });
    await loading.present();

    const payload = {
      ...this.paymentForm.value,
      latitude: position.coords.latitude,
      longitude: position.coords.longitude,
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

  // --- Logika Izin Lokasi ---
  async checkLocationPermission() {
    const permission = await navigator.permissions.query({ name: 'geolocation' });
    if (permission.state === 'granted') {
      this.requestLocation();
    } else {
      this.showLocationModal = true;
    }
  }

  enableLocation() {
    this.showLocationModal = false;
    this.requestLocation();
  }
  
  private async requestLocation() {
    const loading = await this.loadingCtrl.create({ message: 'Mendapatkan lokasi...' });
    await loading.present();
    try {
      const position = await new Promise<GeolocationPosition>((resolve, reject) => {
        navigator.geolocation.getCurrentPosition(resolve, reject, {
          enableHighAccuracy: true,
          timeout: 15000,
          maximumAge: 0
        });
      });
      loading.dismiss();
      this.proceedWithSubmission(position); // Lanjutkan setelah lokasi didapat
    } catch (error: any) {
      loading.dismiss();
      this.presentToast(error.message || 'Gagal mendapatkan lokasi. Pastikan GPS aktif.', 'danger');
    }
  }

  async presentToast(message: string, color: string = 'danger') {
    const toast = await this.toastCtrl.create({
      message,
      duration: 3000,
      position: 'top',
      color,
    });
    await toast.present();
  }
}
