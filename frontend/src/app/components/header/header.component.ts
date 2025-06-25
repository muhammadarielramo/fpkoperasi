import { Component, OnInit, OnDestroy, ChangeDetectorRef } from '@angular/core';
import { Router, NavigationEnd } from '@angular/router';
import { Subscription } from 'rxjs';
import { filter } from 'rxjs/operators';
import { AuthService } from '../../services/auth.service';
import { NotificationService } from 'src/app/services/notification.service';

@Component({
  standalone: false,
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.scss'],
})
export class HeaderComponent implements OnInit, OnDestroy {
  userName: string = 'Guest';
  userRole: string = 'User';
  notificationCount: number = 0;

  public hasExtraPadding: boolean = false;
  private currentUrl: string = '';
  private routerSubscription: Subscription | undefined;
  private notificationInterval: any;

  constructor(
    private router: Router, 
    private authService: AuthService,
    private notificationService: NotificationService,
    private cdr: ChangeDetectorRef
  ) {}

  async ngOnInit() {
    // Dengarkan perubahan rute untuk memperbarui seluruh state header
    this.routerSubscription = this.router.events
      .pipe(filter((event) => event instanceof NavigationEnd))
      .subscribe((event: NavigationEnd) => {
        // Panggil fungsi pembaruan setiap kali navigasi selesai
        this.updateHeaderState(event.urlAfterRedirects);
      });
  }

  ionViewWillEnter() {
    this.updateHeaderState(this.router.url);
    this.loadNotifications();
    this.notificationInterval = setInterval(() => {
      this.loadNotifications();
    }, 15000);
  }

  ionViewDidLeave() {
    if (this.notificationInterval) {
      clearInterval(this.notificationInterval);
    }
  }

  ngOnDestroy() {
    if (this.routerSubscription) {
      this.routerSubscription.unsubscribe();
    }
    if (this.notificationInterval) {
      clearInterval(this.notificationInterval);
    }
  }

  private async updateHeaderState(url: string) {
    this.currentUrl = url;
    
    // 1. Perbarui padding
    this.hasExtraPadding =
      this.currentUrl.includes('/collector/') &&
      !this.currentUrl.includes('/collector/notifications');
      
    // 2. Muat ulang data pengguna
    await this.loadUserData();
  }

  async loadUserData() {
    this.userName = (await this.authService.getUsername()) || 'Pengguna';
    const roleId = await this.authService.getRole();

    const roleAsNumber = parseInt(roleId || '0', 10);

    if (roleAsNumber === 2) {
      this.userRole = 'Collector';
    } else if (roleAsNumber === 3) {
      this.userRole = 'Member';
    } else {
      this.userRole = 'User';
    }
    this.cdr.detectChanges(); // Paksa pembaruan tampilan
  }

  async loadNotifications() {
    (await this.notificationService.getNotifications()).subscribe({
      next: (res: any) => {
        const unreadCount = res.data?.filter((notif: any) => notif.read_at === null).length || 0;
        this.notificationCount = unreadCount;
        this.cdr.detectChanges();
      },
      error: (err) => {
        console.error('Gagal mengambil notifikasi:', err);
        this.notificationCount = 0;
      }
    });
  }

  // Method untuk menentukan rute notifikasi berdasarkan peran
  getNotificationRoute(): string {
    if (this.currentUrl.includes('/collector/')) {
      return '/collector/notifications';
    }
    return '/member/notifications';
  }

  getNotificationIconName(): string {
    return this.currentUrl.includes('/notifications')
      ? 'notifications'
      : 'notifications-outline';
  }

  async logout() {
    await this.authService.logout();
    this.router.navigate(['/login'], { replaceUrl: true });
  }
}
