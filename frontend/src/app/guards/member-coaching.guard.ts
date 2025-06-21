import { Injectable } from '@angular/core';
import { CanActivate, ActivatedRouteSnapshot, Router } from '@angular/router';
import { ToastController } from '@ionic/angular';
import { CollectorService } from '../services/collector.service';
import { firstValueFrom } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class MemberCoachingGuard implements CanActivate {

  constructor(
    private collectorService: CollectorService,
    private router: Router,
    private toastCtrl: ToastController
  ) { }

  async canActivate(route: ActivatedRouteSnapshot): Promise<boolean> {
    // 1. Dapatkan path untuk redirect dari data rute, dengan fallback
    const redirectPath = route.data['redirectTo'] || '/collector/dashboard';

    const memberIdToAccess = parseInt(route.paramMap.get('id')!, 10);
    if (isNaN(memberIdToAccess)) {
      this.router.navigate([redirectPath]);
      return false;
    }

    try {
      const coachedMembersObservable = await this.collectorService.getCoachedMembers();
      const coachedMembersResponse = await firstValueFrom(coachedMembersObservable);
      
      const coachedMembers = coachedMembersResponse.data || [];
      
      const isAuthorized = coachedMembers.some((item: any) => item.id === memberIdToAccess);

      if (isAuthorized) {
        return true; // Izinkan akses
      } else {
        // Jika tidak, tolak dan arahkan ke path yang sesuai
        await this.presentToast('Anda tidak memiliki izin untuk anggota ini.');
        this.router.navigate([redirectPath]);
        return false;
      }
    } catch (error) {
      // Tangani jika gagal memuat daftar anggota
      await this.presentToast('Gagal memverifikasi izin anggota.');
      this.router.navigate([redirectPath]); // Arahkan ke path yang sesuai
      return false;
    }
  }

  async presentToast(message: string) {
    const toast = await this.toastCtrl.create({
      message,
      duration: 3000,
      position: 'top',
      color: 'danger'
    });
    await toast.present();
  }
}
