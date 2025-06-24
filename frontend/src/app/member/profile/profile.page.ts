import { Component, OnInit, ElementRef, ViewChild } from '@angular/core';
import { AlertController, ToastController, LoadingController } from '@ionic/angular';
import { MemberService } from 'src/app/services/member.service';
import { AuthService } from 'src/app/services/auth.service';
import { Router } from '@angular/router';
import { environment } from 'src/environments/environment';

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
  private avatarFile: File | null = null;
  private storageBaseUrl: string; // Properti untuk menyimpan URL dasar storage

  constructor(
    private alertController: AlertController,
    private toastController: ToastController,
    private memberService: MemberService,
    private authService: AuthService,
    private router: Router,
    private loadingCtrl: LoadingController
  ) {
    // 2. Buat URL dasar untuk storage dari apiUrl
    this.storageBaseUrl = environment.apiUrl.replace('/api', '/storage');
  }

  ngOnInit() {}

  ionViewWillEnter() {
    this.loadProfileData();
  }

  async loadProfileData() {
    this.isLoading = true;
    (await this.memberService.getProfile()).subscribe({
      next: (res: any) => {
        if (res && res.data) {
          let avatarUrl = `https://placehold.co/100x100/EFEFEF/333333?text=${res.data.username ? res.data.username.charAt(0).toUpperCase() : '?'}`;
          // Memperbaiki URL avatar
          if (res.data.avatar) {
            let path = res.data.avatar.startsWith('http') ? res.data.avatar : `${this.storageBaseUrl}/${res.data.avatar}`;
            avatarUrl = path.replace('http://', 'https://');
          }

          this.profileData = {
            username: res.data.username,
            email: res.data.email,
            alamat: res.data.member?.address,
            noHp: res.data.phone_number,
            avatar: avatarUrl,
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

    const formData = new FormData();
    const emailChanged = this.profileData.email !== this.originalProfileData.email;

    formData.append('username', this.profileData.username);
    formData.append('address', this.profileData.alamat);
    formData.append('phone_number', this.profileData.noHp);

    if (this.avatarFile) {
      formData.append('avatar', this.avatarFile, this.avatarFile.name);
    }

    if (emailChanged) {
      formData.append('email', this.profileData.email);
    }

    (await this.memberService.updateProfile(formData)).subscribe({
      next: (res: any) => {
        loading.dismiss();
        if (res && res.message === 'Email verifikasi telah dikirim ke alamat baru Anda.') {
          this.handleEmailChangeSuccess(res.message);
        } else if (res && res.success) {
          this.presentToast('Profil berhasil diperbarui!', 'success');
          this.isEditMode = false;
          // PERBAIKAN: Panggil kembali loadProfileData() untuk menyinkronkan tampilan
          this.loadProfileData(); 
        } else {
          const errorMessages = Object.values(res.messages || {'error':['Terjadi kesalahan']}).reduce((acc: any[], val: any) => acc.concat(val), []).join('\n');
          this.presentToast(errorMessages, 'danger');
        }
      },
      error: (err) => {
        loading.dismiss();
        this.presentToast('Terjadi kesalahan. Silakan coba lagi.', 'danger');
      }
    });
  }

  async handleEmailChangeSuccess(message: string) {
    this.presentToast(message, 'success');
    this.isEditMode = false;
    // Panggil ulang data untuk memastikan tampilan kembali ke data email yang lama
    this.loadProfileData();
  }

  cancelEdit(force: boolean = false) {
    this.avatarFile = null;
    this.profileData = { ...this.originalProfileData };
    if (!force) {
      this.presentToast('Perubahan dibatalkan');
    }
    this.isEditMode = false;
  }

  editAvatar() {
    this.fileInput.nativeElement.click();
  }

  onFileSelected(event: any) {
    const file = event.target.files[0];
    if (file) {
      this.avatarFile = file;
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
