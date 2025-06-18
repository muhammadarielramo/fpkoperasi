import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { TabsPage } from './tabs.page';
import { LocationGuard } from 'src/app/guards/location.guard';

const routes: Routes = [
  // --- Rute untuk Halaman dengan Tab Bar ---
  {
    path: '', // Menangani /member
    component: TabsPage,
    children: [
      {
        path: 'dashboard', // URL: /member/dashboard
        loadChildren: () =>
          import('../dashboard/dashboard.module').then(
            (m) => m.DashboardPageModule
          ),
      },
      {
        path: 'savings', // URL: /member/savings
        loadChildren: () =>
          import('../savings/savings.module').then((m) => m.SavingsPageModule),
      },
      {
        path: 'loans', // URL: /member/loans
        loadChildren: () =>
          import('../loans/loans.module').then((m) => m.LoansPageModule),
      },
      {
        path: 'histories', // URL: /member/histories
        loadChildren: () =>
          import('../histories/histories.module').then(
            (m) => m.HistoriesPageModule
          ),
      },
      {
        path: 'profile', // URL: /member/profile
        loadChildren: () =>
          import('../profile/profile.module').then((m) => m.ProfilePageModule),
      },
      {
        path: 'notifications', // URL: /member/notifications
        loadChildren: () =>
          import('../notifications/notifications.module').then(
            (m) => m.NotificationsPageModule
          ),
      },
      {
        // Redirect default untuk /member
        path: '',
        redirectTo: 'dashboard',
        pathMatch: 'full',
      },
    ],
  },

  // --- Rute untuk Halaman Detail (Tanpa Tab Bar) ---
  // Rute ini didefinisikan di luar 'children' agar tidak menampilkan tab.
  {
    path: 'loans/loan-application',
    canActivate: [LocationGuard], // URL: /member/loans/loan-application
    loadChildren: () =>
      import('../loans/loan-application/loan-application.module').then(
        (m) => m.LoanApplicationPageModule
      ),
  },
  {
    path: 'loans/billing-details/:id', // URL: /member/loans/billing-details/123
    loadChildren: () =>
      import('../loans/billing-details/billing-details.module').then(
        (m) => m.BillingDetailsPageModule
      ),
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class TabsPageRoutingModule {}
