import { Component, OnInit, ElementRef, ViewChild } from '@angular/core';
import { AlertController, ToastController, LoadingController } from '@ionic/angular';
import { MemberService } from 'src/app/services/member.service';

@Component({
  standalone: false,
  selector: 'app-profile',
  templateUrl: './profile.page.html',
  styleUrls: ['./profile.page.scss'],
})
export class ProfilePage implements OnInit {
  @ViewChild('fileInput', { static: false }) fileInput!: ElementRef;

  isEditMode = false;
  isLoading = true;
  
  profileData: any = {};
  originalProfileData: any = {};

  constructor(
    private alertController: AlertController,
    private toastController: ToastController,
    private memberService: MemberService,
    private loadingCtrl: LoadingController
  ) {}

  ngOnInit() {}

  ionViewWillEnter() {
    this.loadProfileData();
  }

  async loadProfileData() {
    this.isLoading = true;
    (await this.memberService.getProfile()).subscribe({
      next: (res: any) => {
        if (res && res.data) {
          this.profileData = {
            username: res.data.name,
            email: res.data.email,
            alamat: res.data.member?.address,
            noHp: res.data.phone_number,
            avatar: res.data.photo_url || `https://placehold.co/100x100/EFEFEF/333333?text=${res.data.name ? res.data.name.charAt(0).toUpperCase() : '?'}`,
          };
          this.originalProfileData = { ...this.profileData };
        }
        this.isLoading = false;
      },
      error: (err) => {
        this.isLoading = false;
        this.presentToast('Gagal memuat profil.', 'danger');
      }
    });
  }

  toggleEditMode() {
    this.isEditMode = !this.isEditMode;
    if (!this.isEditMode) {
      this.cancelEdit(true);
    }
  }

  async saveChanges() {
    const loading = await this.loadingCtrl.create({ message: 'Menyimpan...' });
    await loading.present();

    const payload: any = {
      username: this.profileData.username,
      address: this.profileData.alamat,
      phone_number: this.profileData.noHp,
    };
    
    if (this.profileData.email !== this.originalProfileData.email) {
      payload.email = this.profileData.email;
    }
    
    // Logika untuk mengirim password telah dihapus

    (await this.memberService.updateProfile(payload)).subscribe({
      next: (res: any) => {
        loading.dismiss();
        if (res && res.success) {
          this.presentToast('Profil berhasil diperbarui!', 'success');
          this.originalProfileData = { ...this.profileData };
          this.isEditMode = false;
        } else {
          let errorMessages = 'Gagal menyimpan perubahan.';
          if (res && res.messages && typeof res.messages === 'object') {
            errorMessages = Object.values(res.messages).reduce((acc: string[], val: any) => acc.concat(val), []).join('\n');
          }
          this.presentToast(errorMessages, 'danger');
        }
      },
      error: (err) => {
        loading.dismiss();
        this.presentToast('Terjadi kesalahan. Silakan coba lagi.', 'danger');
      }
    });
  }

  cancelEdit(force: boolean = false) {
    if (force) {
      this.profileData = { ...this.originalProfileData };
      this.isEditMode = false;
      return;
    }
    this.presentToast('Perubahan dibatalkan');
    this.profileData = { ...this.originalProfileData };
    this.isEditMode = false;
  }

  editAvatar() {
    this.fileInput.nativeElement.click();
  }

  onFileSelected(event: any) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (e: any) => this.profileData.avatar = e.target.result;
      reader.readAsDataURL(file);
    }
  }

  async presentToast(message: string, color: string = 'medium') {
    const toast = await this.toastController.create({
      message,
      duration: 3000,
      position: 'bottom',
      color,
    });
    toast.present();
  }
}
