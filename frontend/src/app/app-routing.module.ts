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
    path: 'dashboard',
    loadChildren: () => import('./member/dashboard/dashboard.module').then(m => m.DashboardPageModule)
  },
  {
    path: 'notifications',
    loadChildren: () => import('./member/notifications/notifications.module').then(m => m.NotificationsPageModule)
  },
  {
    path: 'payments',
    loadChildren: () => import('./member/payments/payments.module').then( m => m.PaymentsPageModule)
  },
  {
    path: 'loans',
    loadChildren: () => import('./member/loans/loans.module').then( m => m.LoansPageModule)
  },
  {
    path: 'histories',
    loadChildren: () => import('./member/histories/histories.module').then( m => m.HistoriesPageModule)
  },
  {
    path: 'forgot-password',
    loadChildren: () => import('./member/forgot-password/forgot-password.module').then( m => m.ForgotPasswordPageModule)
  },
  {
    path: 'dashboard',
    loadChildren: () => import('./admin/dashboard/dashboard.module').then( m => m.DashboardPageModule)
  },  {
    path: 'profile',
    loadChildren: () => import('./member/profile/profile.module').then( m => m.ProfilePageModule)
  }


];

@NgModule({
  imports: [
    RouterModule.forRoot(routes, { preloadingStrategy: PreloadAllModules })
  ],
  exports: [RouterModule]
})
export class AppRoutingModule { }
