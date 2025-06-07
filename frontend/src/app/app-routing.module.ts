import { NgModule } from '@angular/core';
import { PreloadAllModules, RouterModule, Routes } from '@angular/router';

const routes: Routes = [
  {
    path: 'splash',
    loadChildren: () => import('./splash/splash.module').then(m => m.SplashPageModule)
  },
  {
    path: '',
    redirectTo: 'splash',
    pathMatch: 'full'
  },
  {
    path: 'home',
    loadChildren: () => import('./home/home.module').then(m => m.HomePageModule)
  },
  {
    path: 'register',
    loadChildren: () => import('./register/register.module').then(m => m.RegisterPageModule)
  },
  {
    path: 'login',
    loadChildren: () => import('./login/login.module').then(m => m.LoginPageModule)
  },
  {
    path: 'member/dashboard',
    loadChildren: () => import('./member/dashboard/dashboard.module').then(m => m.DashboardPageModule)
  },
  {
    path: 'member/notifications',
    loadChildren: () => import('./member/notifications/notifications.module').then(m => m.NotificationsPageModule)
  },
  {
    path: 'member/savings',
    loadChildren: () => import('./member/savings/savings.module').then( m => m.SavingsPageModule)
  },
  {
    path: 'member/loans',
    loadChildren: () => import('./member/loans/loans.module').then( m => m.LoansPageModule)
  },
  {
    path: 'member/histories',
    loadChildren: () => import('./member/histories/histories.module').then( m => m.HistoriesPageModule)
  },
  {
    path: 'login/forgot-password',
    loadChildren: () => import('./login/forgot-password/forgot-password.module').then( m => m.ForgotPasswordPageModule)
  },
  {
    path: 'member/profile',
    loadChildren: () => import('./member/profile/profile.module').then( m => m.ProfilePageModule)
  },
  {
    path: 'member/loans/loan-application',
    loadChildren: () => import('./member/loans/loan-application/loan-application.module').then(m => m.LoanApplicationPageModule)
  },
  {
    path: 'member/loans/billing-details',
    loadChildren: () => import('./member/loans/billing-details/billing-details.module').then(m => m.BillingDetailsPageModule)
  },
  {
    path: 'collector/dashboard',
    loadChildren: () => import('./collector/dashboard/dashboard.module').then( m => m.DashboardPageModule)
  },
  {
    path: 'collector/savings',
    loadChildren: () => import('./collector/savings/savings.module').then( m => m.SavingsPageModule)
  },
  {
    path: 'collector/savings/deposit-savings',
    loadChildren: () => import('./collector/savings/deposit-savings/deposit-savings.module').then( m => m.DepositSavingsPageModule)
  },
  {
    path: 'collector/loans/payment-entry',
    loadChildren: () => import('./collector/loans/payment-entry/payment-entry.module').then( m => m.PaymentEntryPageModule)
  },
  {
    path: 'collector/loans',
    loadChildren: () => import('./collector/loans/loans.module').then( m => m.LoansPageModule)
  },
  {
    path: 'collector/loans/loan-details',
    loadChildren: () => import('./collector/loans/loan-details/loan-details.module').then( m => m.LoanDetailsPageModule)
  },
  {
    path: 'collector/notifications',
    loadChildren: () => import('./collector/notifications/notifications.module').then( m => m.NotificationsPageModule)
  },
  {
    path: 'collector/histories',
    loadChildren: () => import('./collector/histories/histories.module').then( m => m.HistoriesPageModule)
  },
  {
    path: 'collector/members',
    loadChildren: () => import('./collector/members/members.module').then( m => m.MembersPageModule)
  }

];

@NgModule({
  imports: [
    RouterModule.forRoot(routes, { preloadingStrategy: PreloadAllModules })
  ],
  exports: [RouterModule]
})
export class AppRoutingModule { }
