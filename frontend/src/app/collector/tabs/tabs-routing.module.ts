import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { LocationGuard } from 'src/app/guards/location.guard'; // 1. Impor LocationGuard
import { TabsPage } from './tabs.page';

const routes: Routes = [
  // --- Rute untuk Halaman dengan Tab Bar ---
  {
    path: '', // Ini akan menangani /collector
    component: TabsPage,
    children: [
      {
        path: 'dashboard', // URL: /collector/dashboard
        loadChildren: () =>
          import('../dashboard/dashboard.module').then(
            (m) => m.DashboardPageModule
          ),
      },
      {
        path: 'savings', // URL: /collector/savings
        loadChildren: () =>
          import('../savings/savings.module').then((m) => m.SavingsPageModule),
      },
      {
        path: 'loans', // URL: /collector/loans
        loadChildren: () =>
          import('../loans/loans.module').then((m) => m.LoansPageModule),
      },
      {
        path: 'histories', // URL: /collector/histories
        loadChildren: () =>
          import('../histories/histories.module').then(
            (m) => m.HistoriesPageModule
          ),
      },
      {
        path: 'members', // URL: /collector/members
        loadChildren: () =>
          import('../members/members.module').then((m) => m.MembersPageModule),
      },
      {
        path: 'notifications', // URL: /collector/notifications
        loadChildren: () =>
          import('../notifications/notifications.module').then(
            (m) => m.NotificationsPageModule
          ),
      },
      {
        // Redirect default untuk /collector
        path: '',
        redirectTo: 'dashboard',
        pathMatch: 'full',
      },
    ],
  },
  
  // --- Rute untuk Halaman Detail (Tanpa Tab Bar) ---
  // Rute ini didefinisikan di luar 'children' agar tidak menampilkan tab.
  {
    path: 'savings/deposit-savings/:id', // URL: /collector/savings/deposit-savings/123
    canActivate: [LocationGuard], // 2. Terapkan LocationGuard di sini
    loadChildren: () =>
      import('../savings/deposit-savings/deposit-savings.module').then(
        (m) => m.DepositSavingsPageModule
      ),
  },
  {
    path: 'loans/loan-details/:id', // URL: /collector/loans/loan-details/456
    loadChildren: () =>
      import('../loans/loan-details/loan-details.module').then(
        (m) => m.LoanDetailsPageModule
      ),
  },
  {
    path: 'loans/payment-entry/:id', // URL: /collector/loans/payment-entry/789
    loadChildren: () =>
      import('../loans/payment-entry/payment-entry.module').then(
        (m) => m.PaymentEntryPageModule
      ),
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class TabsPageRoutingModule {}
