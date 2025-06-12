import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { TabsPage } from './tabs.page';

const routes: Routes = [
  {
    // Rute induknya adalah TabsPage
    path: '',
    component: TabsPage,
    // Halaman-halaman berikut adalah "anak" dari TabsPage
    children: [
      {
        path: 'dashboard', // URL menjadi: /member/dashboard
        loadChildren: () => import('../dashboard/dashboard.module').then(m => m.DashboardPageModule)
      },
      {
        path: 'savings', // URL menjadi: /member/savings
        loadChildren: () => import('../savings/savings.module').then(m => m.SavingsPageModule)
      },
      {
        path: 'loans', // URL menjadi: /member/loans
        loadChildren: () => import('../loans/loans.module').then(m => m.LoansPageModule)
      },
      {
        path: 'histories', // URL menjadi: /member/histories
        loadChildren: () => import('../histories/histories.module').then(m => m.HistoriesPageModule)
      },
      {
        path: 'profile', // URL menjadi: /member/profile
        loadChildren: () => import('../profile/profile.module').then(m => m.ProfilePageModule)
      },
      {
        // Jika pengguna membuka /member, arahkan otomatis ke /member/dashboard
        path: '',
        redirectTo: 'dashboard',
        pathMatch: 'full'
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class TabsPageRoutingModule {}