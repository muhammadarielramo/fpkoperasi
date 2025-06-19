import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ToastController } from '@ionic/angular';
import { NotificationService } from 'src/app/services/notification.service';

@Component({
  standalone: false,
  selector: 'app-notifications',
  templateUrl: './notifications.page.html',
  styleUrls: ['./notifications.page.scss'],
})
export class NotificationsPage implements OnInit {
  public notifications: any[] = [];
  public isLoading: boolean = true;

  constructor(
    private notificationService: NotificationService,
    private toastCtrl: ToastController,
    private router: Router
  ) {}

  ngOnInit() {}

  // Gunakan ionViewWillEnter agar data selalu segar setiap kali halaman dibuka
  ionViewWillEnter() {
    this.loadNotifications();
  }

  async loadNotifications(event?: any) {
    this.isLoading = true;
    (await this.notificationService.getNotifications()).subscribe({
      next: (res: any) => {
        if (res && Array.isArray(res.data)) {
          this.notifications = res.data;
        } else {
          this.notifications = [];
        }
        this.isLoading = false;
        if (event) event.target.complete();
      },
      error: (err) => {
        this.isLoading = false;
        if (event) event.target.complete();
        this.presentToast('Gagal memuat notifikasi.');
      },
    });
  }

  /**
   * Menangani saat sebuah notifikasi diklik.
   * Jika belum dibaca, akan ditandai sebagai sudah dibaca.
   */
  async onNotificationClick(notification: any) {
    // Jika notifikasi belum dibaca (read_at masih null)
    if (notification.read_at === null) {
      // Optimistic UI: langsung ubah tampilan sebelum menunggu respons API
      notification.read_at = new Date().toISOString(); 
      
      // Panggil API untuk menandai sebagai sudah dibaca
      (await this.notificationService.markAsRead(notification.id)).subscribe({
        next: (res: any) => {
          // Tidak perlu melakukan apa-apa jika sukses, UI sudah diubah
          console.log('Notifikasi ditandai sebagai sudah dibaca');
        },
        error: (err) => {
          // Jika gagal, kembalikan ke status belum dibaca dan beri tahu pengguna
          notification.read_at = null;
          this.presentToast('Gagal menandai notifikasi.');
          console.error(err);
        },
      });
    }

    // Navigasi ke halaman detail jika diperlukan di masa depan
    // this.router.navigate(['/path/to/detail', notification.id]);
  }

  handleRefresh(event: any) {
    this.loadNotifications(event);
  }

  async presentToast(message: string) {
    const toast = await this.toastCtrl.create({
      message,
      duration: 3000,
      position: 'top',
      color: 'danger',
    });
    await toast.present();
  }
}
