import {
  Component,
  Input,
  OnInit,
  OnDestroy,
  Output,
  EventEmitter,
} from '@angular/core';
import { Router, NavigationEnd } from '@angular/router';
import { Subscription } from 'rxjs';
import { filter } from 'rxjs/operators';

@Component({
  standalone: false,
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.scss'],
})
export class HeaderComponent implements OnInit, OnDestroy {
  @Input() userName: string = 'Guest';
  @Input() userRole: string = 'User';
  @Input() notificationCount: number = 0;
  @Output() onLogout = new EventEmitter<void>();

  public hasExtraPadding: boolean = false;
  public currentUrl: string = '';
  private routerSubscription: Subscription | undefined;

  constructor(private router: Router) {}

  ngOnInit() {
    // Set initial URL
    this.currentUrl = this.router.url;
    this.updateUserRole(); // Set initial user role
    
    // Listen to router events
    this.routerSubscription = this.router.events
      .pipe(filter((event) => event instanceof NavigationEnd))
      .subscribe((event: NavigationEnd) => {
        this.currentUrl = event.urlAfterRedirects;

        // Update padding
        if (
          this.currentUrl.includes('/collector/') &&
          !this.currentUrl.includes('/collector/notifications')
        ) {
          this.hasExtraPadding = true;
        } else {
          this.hasExtraPadding = false;
        }

        // Update user role based on current URL
        this.updateUserRole();
      });
  }

  ngOnDestroy() {
    if (this.routerSubscription) {
      this.routerSubscription.unsubscribe();
    }
  }

  // Method to update user role based on current URL
  private updateUserRole(): void {
    if (this.currentUrl.includes('/collector/')) {
      this.userRole = 'Collector';
    } else if (this.currentUrl.includes('/member/')) {
      this.userRole = 'Member';
    }
    // If neither collector nor member, keep the original userRole value
  }

  // Method to determine notification icon name based on current page
  getNotificationIconName(): string {
    if (this.currentUrl.includes('/notifications')) {
      return 'notifications';
    }
    return 'notifications-outline';
  }

  // Method to determine notification route based on current page
  getNotificationRoute(): string {
    if (this.currentUrl.includes('/collector/')) {
      return '/collector/notifications';
    }
    return '/member/notifications';
  }

  logout() {
    console.log('Logout button clicked');
    this.onLogout.emit();
    this.router.navigate(['/home']);
  }
}