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
    path: 'forgot-password',
    loadChildren: () => import('./login/forgot-password/forgot-password.module').then( m => m.ForgotPasswordPageModule)
  },
  {
    path: 'member/profile',
    loadChildren: () => import('./member/profile/profile.module').then( m => m.ProfilePageModule)
  },
  {
    path: 'member/loan-application',
    loadChildren: () => import('./member/loans/loan-application/loan-application.module').then(m => m.LoanApplicationPageModule)
  },  {
    path: 'dashboard',
    loadChildren: () => import('./collector/dashboard/dashboard.module').then( m => m.DashboardPageModule)
  },
  {
    path: 'savings',
    loadChildren: () => import('./collector/savings/savings.module').then( m => m.SavingsPageModule)
  },
  {
    path: 'payments',
    loadChildren: () => import('./collector/payments/payments.module').then( m => m.PaymentsPageModule)
  },
  {
    path: 'loans',
    loadChildren: () => import('./collector/loans/loans.module').then( m => m.LoansPageModule)
  },
  {
    path: 'notifications',
    loadChildren: () => import('./collector/notifications/notifications.module').then( m => m.NotificationsPageModule)
  },
  {
    path: 'histories',
    loadChildren: () => import('./collector/histories/histories.module').then( m => m.HistoriesPageModule)
  }

];

@NgModule({
  imports: [
    RouterModule.forRoot(routes, { preloadingStrategy: PreloadAllModules })
  ],
  exports: [RouterModule]
})
export class AppRoutingModule { }
