import { Component, OnInit, ViewEncapsulation, ViewChild } from '@angular/core';
import { Router } from '@angular/router';
import { DomSanitizer, SafeResourceUrl } from '@angular/platform-browser';
import { Camera, CameraResultType, CameraSource } from '@capacitor/camera';

@Component({
  standalone: false,
  selector: 'app-register',
  templateUrl: './register.page.html',
  styleUrls: ['./register.page.scss'],
  encapsulation: ViewEncapsulation.None,
})
export class RegisterPage implements OnInit {
  showVerification = false;
  showDatePicker = false;
  selectedDate = '';
  showPassword = false;

  idCardImageUrl: SafeResourceUrl | null = null;

  // Variabel untuk menyimpan nilai yang dipilih
  selectedGender: string = '';

  constructor(
    private router: Router,
    private sanitizer: DomSanitizer // Menambahkan DomSanitizer
  ) {}

  ngOnInit() {}

  /**
   * Opens the device's camera to take a photo.
   */
  async takePictureWithCamera() {
    try {
      const image = await Camera.getPhoto({
        quality: 100,
        allowEditing: false,
        resultType: CameraResultType.Uri,
        source: CameraSource.Camera, // Menentukan sumber adalah kamera
      });

      // FIX: Check if image.webPath exists before using it.
      if (image.webPath) {
        // Sanitasi webPath agar aman ditampilkan di tag <img>.
        this.idCardImageUrl = this.sanitizer.bypassSecurityTrustResourceUrl(
          image.webPath
        );
      }
    } catch (error) {
      console.error('Error taking picture with camera:', error);
    }
  }

  /**
   * Opens the device's photo gallery to select an image.
   */
  async selectPictureFromGallery() {
    try {
      const image = await Camera.getPhoto({
        quality: 100,
        allowEditing: false,
        resultType: CameraResultType.Uri,
        source: CameraSource.Photos, // Menentukan sumber adalah galeri
      });

      // FIX: Check if image.webPath exists before using it.
      if (image.webPath) {
        this.idCardImageUrl = this.sanitizer.bypassSecurityTrustResourceUrl(
          image.webPath
        );
      }
    } catch (error) {
      console.error('Error selecting picture from gallery:', error);
    }
  }

  /**
   * Clears the currently selected ID card image.
   */
  clearImage() {
    this.idCardImageUrl = null;
  }

  showVerificationAlert() {
    this.showVerification = true;

    // Redirect after 5 seconds
    setTimeout(() => {
      this.router.navigate(['/home']);
    }, 5000);
  }

  openDatePicker() {
    this.showDatePicker = true;
  }

  setDateValue(event: any) {
    if (event && event.detail && event.detail.value) {
      // Format date as DD/MM/YYYY
      const dateValue = event.detail.value;

      // Check if the dateValue is a valid date string
      if (typeof dateValue === 'string' && dateValue.trim() !== '') {
        const date = new Date(dateValue);

        // Check if date is valid
        if (!isNaN(date.getTime())) {
          const day = String(date.getDate()).padStart(2, '0');
          const month = String(date.getMonth() + 1).padStart(2, '0');
          const year = date.getFullYear();

          this.selectedDate = `${day}/${month}/${year}`;

          // Set the value to the input field
          const inputElement = document.getElementById(
            'tanggalLahirInput'
          ) as HTMLIonInputElement;
          if (inputElement) {
            inputElement.value = this.selectedDate;
          }
        }
      }
    }

    this.showDatePicker = false;
  }

  togglePasswordVisibility() {
    this.showPassword = !this.showPassword;
  }

  // Fungsi ini dipanggil ketika salah satu item di-klik
  selectGender(gender: string) {
    // 1. Atur nilai yang dipilih
    this.selectedGender = gender;

    console.log('Jenis kelamin terpilih:', this.selectedGender);
  }
}
