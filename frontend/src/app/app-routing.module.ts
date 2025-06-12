import { NgModule } from '@angular/core';
import { PreloadAllModules, RouterModule, Routes } from '@angular/router';

const routes: Routes = [
  // --- Rute Awal dan Umum (Tidak Berubah) ---
  {
    path: 'splash',
    loadChildren: () => import('./splash/splash.module').then((m) => m.SplashPageModule),
  },
  {
    path: '',
    redirectTo: 'splash',
    pathMatch: 'full',
  },
  {
    path: 'home',
    loadChildren: () => import('./home/home.module').then((m) => m.HomePageModule),
  },
  {
    path: 'register',
    loadChildren: () => import('./register/register.module').then((m) => m.RegisterPageModule),
  },
  {
    path: 'login',
    loadChildren: () => import('./login/login.module').then((m) => m.LoginPageModule),
  },
  {
    path: 'login/forgot-password',
    loadChildren: () => import('./login/forgot-password/forgot-password.module').then( (m) => m.ForgotPasswordPageModule ),
  },

  // --- Rute Induk untuk MEMBER dengan Tab Bar ---
  {
    path: 'member',
    loadChildren: () => import('./member/tabs/tabs.module').then(m => m.TabsPageModule)
  },

  // --- Rute Induk untuk COLLECTOR dengan Tab Bar ---
  {
    path: 'collector',
    loadChildren: () => import('./collector/tabs/tabs.module').then(m => m.TabsPageModule)
  },

  // --- Rute Member Tanpa Tab Bar (Halaman Penuh) ---
  {
    path: 'member/notifications',
    loadChildren: () => import('./member/notifications/notifications.module').then( (m) => m.NotificationsPageModule ),
  },
  {
    path: 'member/loans/loan-application',
    loadChildren: () => import('./member/loans/loan-application/loan-application.module').then( (m) => m.LoanApplicationPageModule ),
  },
  {
    path: 'member/loans/billing-details',
    loadChildren: () => import('./member/loans/billing-details/billing-details.module').then( (m) => m.BillingDetailsPageModule ),
  },
  
  // --- Rute Collector Tanpa Tab Bar (Halaman Penuh) ---
  {
    path: 'collector/notifications',
    loadChildren: () => import('./collector/notifications/notifications.module').then( (m) => m.NotificationsPageModule ),
  },
  {
    path: 'collector/savings/deposit-savings',
    loadChildren: () => import('./collector/savings/deposit-savings/deposit-savings.module').then( (m) => m.DepositSavingsPageModule ),
  },
  {
    path: 'collector/loans/payment-entry',
    loadChildren: () => import('./collector/loans/payment-entry/payment-entry.module').then( (m) => m.PaymentEntryPageModule ),
  },
  {
    path: 'collector/loans/loan-details',
    loadChildren: () => import('./collector/loans/loan-details/loan-details.module').then( (m) => m.LoanDetailsPageModule ),
  },
];

@NgModule({
  imports: [
    RouterModule.forRoot(routes, { preloadingStrategy: PreloadAllModules }),
  ],
  exports: [RouterModule],
})
export class AppRoutingModule {}