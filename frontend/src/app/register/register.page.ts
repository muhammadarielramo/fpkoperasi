import { Component, OnInit, ViewEncapsulation, ViewChild } from '@angular/core';
import { Router } from '@angular/router';
import { DomSanitizer, SafeResourceUrl } from '@angular/platform-browser';
import {
  Camera,
  CameraResultType,
  CameraSource,
  Photo,
} from '@capacitor/camera';
import {
  AlertController,
  LoadingController,
  ToastController,
} from '@ionic/angular';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { AuthService } from '../services/auth.service';

@Component({
  standalone: false,
  selector: 'app-register',
  templateUrl: './register.page.html',
  styleUrls: ['./register.page.scss'],
  encapsulation: ViewEncapsulation.None,
})
export class RegisterPage implements OnInit {
  registerForm: FormGroup;
  showVerification = false;
  showDatePicker = false;
  selectedDate = '';
  showPassword = false;
  selectedGender: string = '';
  idCardImageUrl: SafeResourceUrl | null = null;

  // Variabel untuk menyimpan error dari server
  serverErrors: any = {};

  constructor(
    private router: Router,
    private sanitizer: DomSanitizer,
    private loadingCtrl: LoadingController,
    private toastCtrl: ToastController,
    private formBuilder: FormBuilder,
    private authService: AuthService
  ) {
    this.registerForm = this.formBuilder.group({
      name: ['', [Validators.required, Validators.maxLength(255)]],
      phone: ['', [Validators.required, Validators.maxLength(15)]],
      email: ['', [Validators.required, Validators.email, Validators.maxLength(255)]],
      address: ['', [Validators.required]],
      nik: ['', [Validators.required, Validators.pattern('^[0-9]{16}$')]], // Validasi 16 digit angka
      gender: ['male', Validators.required], // 'L' untuk Laki-laki, 'P' untuk Perempuan
      bod: ['', Validators.required], // Birth of Date (Tanggal Lahir)
      ktp: [null, Validators.required] // Untuk menyimpan file gambar
    });
  }

  ngOnInit() {}
  /**
   * PERBAIKAN: Fungsi publik untuk memilih gambar dari galeri.
   * Fungsi ini akan dipanggil dari file HTML.
   */
  selectFromGallery() {
    this.takePicture(CameraSource.Photos);
  }

  /**
   * PERBAIKAN: Fungsi publik untuk mengambil foto dengan kamera.
   * Fungsi ini akan dipanggil dari file HTML.
   */
  captureWithCamera() {
    this.takePicture(CameraSource.Camera);
  }

  /**
   * Fungsi private untuk menangani logika pengambilan gambar.
   * Hanya bisa diakses dari dalam class ini.
   */
  private async takePicture(source: CameraSource) {
    try {
      const image = await Camera.getPhoto({
        quality: 100,
        allowEditing: false,
        resultType: CameraResultType.Uri,
        source: source,
      });

      if (image && image.webPath) {
        this.idCardImageUrl = this.sanitizer.bypassSecurityTrustResourceUrl(image.webPath);
        
        const blob = await this.uriToBlob(image.webPath);
        this.registerForm.patchValue({ ktp: blob });
        this.registerForm.get('ktp')?.updateValueAndValidity();
      }
    } catch (error) {
      console.error('Error taking picture:', error);
      this.presentToast('Gagal mengambil gambar.');
    }
  }

  private async uriToBlob(uri: string): Promise<Blob> {
    const response = await fetch(uri);
    return await response.blob();
  }

  clearImage() {
    this.idCardImageUrl = null;
    this.registerForm.patchValue({ ktp: null });
    this.registerForm.get('ktp')?.updateValueAndValidity();
  }
  
  setDateValue(event: any) {
    const dateValue = event.detail.value;
    const date = new Date(dateValue);
    const formattedDate = date.toISOString().split('T')[0];
    this.registerForm.patchValue({ bod: formattedDate });
    this.showDatePicker = false;
  }

  async register() {
    if (this.registerForm.invalid) {
      this.presentToast('Harap lengkapi semua data dengan benar.');
      return;
    }

    const loading = await this.loadingCtrl.create({ message: 'Mendaftarkan...' });
    await loading.present();

    const formData = new FormData();
    Object.keys(this.registerForm.value).forEach(key => {
      formData.append(key, this.registerForm.value[key]);
    });

    this.authService.register(formData).subscribe({
      next: (res) => {
        loading.dismiss();
        this.showVerification = true;
        setTimeout(() => this.router.navigate(['/login']), 5000);
      },
      error: (err) => {
        loading.dismiss();
        console.error(err);
        if (err.status === 500 && err.error && err.error.data) {
          this.serverErrors = err.error.data;
          this.presentToast('Data tidak valid. Silakan periksa kembali isian Anda.');
        } else {
          this.presentToast('Terjadi kesalahan. Coba lagi nanti.');
        }
      }
    });
  }

  async presentToast(message: string) {
    const toast = await this.toastCtrl.create({
      message: message,
      duration: 3000,
      position: 'top',
    });
    toast.present();
  }
}
