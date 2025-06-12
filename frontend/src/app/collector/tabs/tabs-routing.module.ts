import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { TabsPage } from './tabs.page';

const routes: Routes = [
  {
    path: '',
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
        path: 'notifications', // URL menjadi: /member/profile
        loadChildren: () =>
          import('../notifications/notifications.module').then(
            (m) => m.NotificationsPageModule
          ),
      },
      {
        // Jika membuka /collector, arahkan ke /collector/dashboard
        path: '',
        redirectTo: 'dashboard',
        pathMatch: 'full',
      },
    ],
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class TabsPageRoutingModule {}
