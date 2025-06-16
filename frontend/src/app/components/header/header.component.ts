import { Component, OnInit, OnDestroy } from '@angular/core';
import { Router, NavigationEnd } from '@angular/router';
import { Subscription } from 'rxjs';
import { filter } from 'rxjs/operators';
import { AuthService } from '../../services/auth.service';

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

  constructor(private router: Router, private authService: AuthService) {}

  async ngOnInit() {
    this.currentUrl = this.router.url;
    await this.loadUserData(); // Muat data saat komponen pertama kali dimuat

    // Dengarkan perubahan rute untuk memperbarui UI secara real-time
    this.routerSubscription = this.router.events
      .pipe(filter((event) => event instanceof NavigationEnd))
      .subscribe(async (event: NavigationEnd) => { // Jadikan callback ini async
        this.currentUrl = event.urlAfterRedirects;

        // Panggil loadUserData() setiap kali navigasi selesai
        await this.loadUserData();

        // Logika untuk padding tambahan berdasarkan URL
        this.hasExtraPadding =
          this.currentUrl.includes('/collector/') &&
          !this.currentUrl.includes('/collector/notifications');
      });
  }

  ngOnDestroy() {
    if (this.routerSubscription) {
      this.routerSubscription.unsubscribe();
    }
  }

  /**
   * Memuat nama dan peran pengguna dari AuthService secara asinkron.
   */
  async loadUserData() {
    this.userName = (await this.authService.getUsername()) || 'Pengguna';
    const roleId = await this.authService.getRole();

    // PERBAIKAN: Ubah peran menjadi angka untuk perbandingan yang andal
    const roleAsNumber = parseInt(roleId || '0', 10);

    if (roleAsNumber === 2) {
      this.userRole = 'Collector';
    } else if (roleAsNumber === 3) {
      this.userRole = 'Member';
    } else {
      this.userRole = 'User'; // Nilai default jika peran tidak dikenali
    }
  }

  // Method untuk menentukan ikon notifikasi berdasarkan halaman
  getNotificationIconName(): string {
    return this.currentUrl.includes('/notifications')
      ? 'notifications'
      : 'notifications-outline';
  }

  // Method untuk menentukan rute notifikasi berdasarkan peran
  getNotificationRoute(): string {
    if (this.currentUrl.includes('/collector/')) {
      return '/collector/notifications';
    }
    return '/member/notifications';
  }

  /**
   * Menangani proses logout secara asinkron.
   */
  async logout() {
    await this.authService.logout();
    this.router.navigate(['/login'], { replaceUrl: true });
  }
}
