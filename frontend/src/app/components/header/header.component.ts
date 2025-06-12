import {
  Component,
  Input,
  OnInit,
  Output,
  EventEmitter,
  OnDestroy,
} from '@angular/core';
import { Router, NavigationEnd } from '@angular/router';
import { Subscription } from 'rxjs'; // Import Subscription
import { filter } from 'rxjs/operators'; // Import filter operator

@Component({
  standalone: false,
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.scss'],
})
export class HeaderComponent implements OnInit {
  // @Input() decorator memungkinkan komponen ini menerima data dari parent.
  @Input() userName: string = 'Guest';

  // INI BAGIAN YANG DIPERBAIKI: Titik setelah @Input sudah dihapus
  @Input() userRole: string = 'User';

  @Input() notificationCount: number = 0;

  // @Output() decorator untuk mengirim event keluar dari komponen
  @Output() onLogout = new EventEmitter<void>();

  // Properti untuk menyimpan status padding
  public hasExtraPadding: boolean = false;
  private routerSubscription: Subscription | undefined;

  constructor(private router: Router) {}

  ngOnInit() {
    // Dengarkan perubahan URL
    this.routerSubscription = this.router.events
      .pipe(filter((event) => event instanceof NavigationEnd))
      .subscribe((event: NavigationEnd) => {
        const currentUrl = event.urlAfterRedirects;

        if (
          currentUrl.includes('/collector/') &&
          !currentUrl.includes('/collector/notifications')
        ) {
          this.hasExtraPadding = true;
        } else {
          this.hasExtraPadding = false;
        }
      });
  }

  // Best practice: Unsubscribe untuk menghindari memory leak
  ngOnDestroy() {
    if (this.routerSubscription) {
      this.routerSubscription.unsubscribe();
    }
  }

  logout() {
    // Di sini Anda bisa menambahkan logika logout (clear storage, panggil API, dll)
    console.log('Logout button clicked');
    this.onLogout.emit(); // Memberi sinyal ke parent bahwa logout terjadi

    // Navigasi ke halaman login/home
    this.router.navigate(['/home']);
  }
}
