import { NgModule } from '@angular/core';
import { PreloadAllModules, RouterModule, Routes } from '@angular/router';
import { RoleGuard } from './guards/role.guard';
import { AuthGuard } from './guards/auth.guard';
// LocationGuard akan kita terapkan di dalam routing module anak jika diperlukan, bukan di sini.

const routes: Routes = [
  // --- Rute Publik (Tidak memerlukan login) ---
  {
    path: 'splash',
    loadChildren: () =>
      import('./splash/splash.module').then((m) => m.SplashPageModule),
  },
  {
    path: '',
    redirectTo: 'splash',
    pathMatch: 'full',
  },
  {
    path: 'home',
    loadChildren: () =>
      import('./home/home.module').then((m) => m.HomePageModule),
  },
  {
    path: 'register',
    loadChildren: () =>
      import('./register/register.module').then((m) => m.RegisterPageModule),
  },
  {
    path: 'login',
    loadChildren: () =>
      import('./login/login.module').then((m) => m.LoginPageModule),
  },
  {
    path: 'login/forgot-password',
    loadChildren: () =>
      import('./login/forgot-password/forgot-password.module').then(
        (m) => m.ForgotPasswordPageModule
      ),
  },

  // --- Rute Induk Member ---
  // Guard hanya diterapkan di sini. Semua halaman di dalam /member akan dilindungi.
  {
    path: 'member',
    canActivate: [AuthGuard, RoleGuard],
    data: { role: 3 }, // Hanya izinkan Member (sebagai ANGKA)
    loadChildren: () =>
      import('./member/tabs/tabs.module').then((m) => m.TabsPageModule),
  },

  // --- Rute Induk Collector ---
  // Guard hanya diterapkan di sini. Semua halaman di dalam /collector akan dilindungi.
  {
    path: 'collector',
    canActivate: [AuthGuard, RoleGuard],
    data: { role: 2 }, // Hanya izinkan Collector (sebagai ANGKA)
    loadChildren: () =>
      import('./collector/tabs/tabs.module').then((m) => m.TabsPageModule),
  },

  // PERHATIAN: Semua deklarasi rute anak seperti 'member/dashboard', 
  // 'collector/savings/deposit-savings/:id', dll. TELAH DIHAPUS dari file ini.
  // Rute-rute tersebut harus didefinisikan di dalam file routing
  // yang dimuat oleh `member/tabs/tabs.module.ts` dan `collector/tabs/tabs.module.ts`.
  // Di sanalah Anda akan menerapkan LocationGuard jika perlu.
];

@NgModule({
  imports: [
    RouterModule.forRoot(routes, { preloadingStrategy: PreloadAllModules }),
  ],
  exports: [RouterModule],
})
export class AppRoutingModule {}
