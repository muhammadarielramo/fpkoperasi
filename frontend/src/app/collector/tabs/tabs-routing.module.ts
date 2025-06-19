import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { LocationGuard } from 'src/app/guards/location.guard';
import { TabsPage } from './tabs.page';
import { MemberCoachingGuard } from 'src/app/guards/member-coaching.guard';

const routes: Routes = [
  // --- Rute untuk Halaman dengan Tab Bar ---
  {
    path: '',
    component: TabsPage,
    children: [
      {
        path: 'dashboard',
        loadChildren: () => import('../dashboard/dashboard.module').then((m) => m.DashboardPageModule),
      },
      {
        path: 'savings',
        loadChildren: () => import('../savings/savings.module').then((m) => m.SavingsPageModule),
      },
      {
        path: 'loans',
        loadChildren: () => import('../loans/loans.module').then((m) => m.LoansPageModule),
      },
      {
        path: 'histories',
        loadChildren: () => import('../histories/histories.module').then((m) => m.HistoriesPageModule),
      },
      {
        path: 'members',
        loadChildren: () => import('../members/members.module').then((m) => m.MembersPageModule),
      },
      {
        path: 'notifications',
        loadChildren: () => import('../notifications/notifications.module').then((m) => m.NotificationsPageModule),
      },
      {
        path: '',
        redirectTo: 'dashboard',
        pathMatch: 'full',
      },
    ],
  },
  
  // --- Rute untuk Halaman Detail (Tanpa Tab Bar) ---
  {
    path: 'savings/deposit-savings/:id',
    canActivate: [LocationGuard, MemberCoachingGuard],
    loadChildren: () => import('../savings/deposit-savings/deposit-savings.module').then((m) => m.DepositSavingsPageModule),
  },
  {
    path: 'loans/loan-details/:id',
    loadChildren: () => import('../loans/loan-details/loan-details.module').then((m) => m.LoanDetailsPageModule),
  },
  {
    path: 'loans/payment-entry/:id',
    canActivate: [LocationGuard],
    loadChildren: () => import('../loans/payment-entry/payment-entry.module').then((m) => m.PaymentEntryPageModule),
  },
  // PERBAIKAN: Tambahkan rute untuk detail anggota di sini
  {
    path: 'members/member-details/:id', // URL: /collector/members/member-details/123
    loadChildren: () => import('../members/member-details/member-details.module').then(m => m.MemberDetailsPageModule)
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class TabsPageRoutingModule {}
