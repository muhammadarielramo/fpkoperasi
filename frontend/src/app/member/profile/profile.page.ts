import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import { AlertController, ToastController } from '@ionic/angular';

@Component({
  standalone: false,
  selector: 'app-profile',
  templateUrl: './profile.page.html',
  styleUrls: ['./profile.page.scss'],
})
export class ProfilePage implements OnInit {
  @ViewChild('fileInput', { static: false }) fileInput!: ElementRef;

  isEditMode = false;
  
  // Original data backup for cancel functionality
  originalProfileData = {
    username: 'Tsukasa Suou',
    email: 'tsukasasuou@gmail.com',
    password: '••••••••••••••',
    alamat: 'Jl. Citra Kebun Mas RT 01/RW 12 KARAWANG',
    noHp: '081234567891012',
    avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&h=100&fit=crop&crop=face'
  };

  // Current profile data that can be edited
  profileData = { ...this.originalProfileData };

  constructor(
    private alertController: AlertController,
    private toastController: ToastController
  ) {}

  ngOnInit() {
  }

  toggleEditMode() {
    this.isEditMode = !this.isEditMode;
    
    // If exiting edit mode without saving, restore original data
    if (!this.isEditMode) {
      this.profileData = { ...this.originalProfileData };
    }
  }

  async editAvatar() {
    const alert = await this.alertController.create({
      header: 'Ubah Avatar',
      message: 'Pilih sumber gambar untuk avatar Anda',
      buttons: [
        {
          text: 'Galeri',
          handler: () => {
            this.selectImageFromGallery();
          }
        },
        {
          text: 'Kamera',
          handler: () => {
            this.takePhoto();
          }
        },
        {
          text: 'Batal',
          role: 'cancel'
        }
      ]
    });

    await alert.present();
  }

  selectImageFromGallery() {
    // Trigger file input click
    this.fileInput.nativeElement.click();
  }

  takePhoto() {
    // In a real app, you would use Capacitor Camera plugin here
    // For now, we'll just show a toast
    this.presentToast('Fitur kamera akan segera tersedia');
  }

  onFileSelected(event: any) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (e: any) => {
        this.profileData.avatar = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  }

  async saveChanges() {
    // Validation
    if (!this.validateProfileData()) {
      return;
    }

    const alert = await this.alertController.create({
      header: 'Konfirmasi',
      message: 'Apakah Anda yakin ingin menyimpan perubahan?',
      buttons: [
        {
          text: 'Batal',
          role: 'cancel'
        },
        {
          text: 'Simpan',
          handler: () => {
            this.confirmSave();
          }
        }
      ]
    });

    await alert.present();
  }

  async confirmSave() {
    try {
      // Here you would typically call your API to save the data
      // For demo purposes, we'll just update the original data
      this.originalProfileData = { ...this.profileData };
      
      // Exit edit mode
      this.isEditMode = false;
      
      // Show success message
      await this.presentToast('Profil berhasil diperbarui!', 'success');
      
    } catch (error) {
      console.error('Error saving profile:', error);
      await this.presentToast('Gagal menyimpan perubahan. Silakan coba lagi.', 'danger');
    }
  }

  async cancelEdit() {
    // Check if there are unsaved changes
    if (this.hasUnsavedChanges()) {
      const alert = await this.alertController.create({
        header: 'Batalkan Perubahan',
        message: 'Apakah Anda yakin ingin membatalkan perubahan? Data yang belum disimpan akan hilang.',
        buttons: [
          {
            text: 'Lanjut Edit',
            role: 'cancel'
          },
          {
            text: 'Batalkan',
            handler: () => {
              this.confirmCancel();
            }
          }
        ]
      });

      await alert.present();
    } else {
      this.confirmCancel();
    }
  }

  confirmCancel() {
    // Restore original data
    this.profileData = { ...this.originalProfileData };
    
    // Exit edit mode
    this.isEditMode = false;
    
    this.presentToast('Perubahan dibatalkan');
  }

  validateProfileData(): boolean {
    // Username validation
    if (!this.profileData.username.trim()) {
      this.presentToast('Username tidak boleh kosong', 'warning');
      return false;
    }

    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(this.profileData.email)) {
      this.presentToast('Format email tidak valid', 'warning');
      return false;
    }

    // Phone number validation
    const phoneRegex = /^[0-9]+$/;
    if (!phoneRegex.test(this.profileData.noHp)) {
      this.presentToast('Nomor HP hanya boleh berisi angka', 'warning');
      return false;
    }

    // Address validation
    if (!this.profileData.alamat.trim()) {
      this.presentToast('Alamat tidak boleh kosong', 'warning');
      return false;
    }

    return true;
  }

  hasUnsavedChanges(): boolean {
    return JSON.stringify(this.profileData) !== JSON.stringify(this.originalProfileData);
  }

  async presentToast(message: string, color: string = 'medium') {
    const toast = await this.toastController.create({
      message: message,
      duration: 2000,
      color: color,
      position: 'bottom'
    });
    toast.present();
  }
}