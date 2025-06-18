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
    const memberIdToAccess = parseInt(route.paramMap.get('id')!, 10);
    if (isNaN(memberIdToAccess)) {
      this.router.navigate(['/collector/savings']); // Arahkan jika ID tidak valid
      return false;
    }

    try {
      // Ambil daftar anggota binaan dari API
      const coachedMembersObservable = await this.collectorService.getCoachedMembers();
      const coachedMembersResponse = await firstValueFrom(coachedMembersObservable);
      
      const coachedMembers = coachedMembersResponse.data || [];
      
      // Cek apakah ID dari URL ada di dalam daftar anggota binaan
      const isAuthorized = coachedMembers.some((item: any) => item.id === memberIdToAccess);

      if (isAuthorized) {
        return true; // Jika ada, izinkan akses
      } else {
        // Jika tidak, tampilkan pesan dan blokir
        await this.presentToast('Anda tidak memiliki izin untuk anggota ini.');
        this.router.navigate(['/collector/savings']);
        return false;
      }
    } catch (error) {
      // Tangani jika gagal memuat daftar anggota
      await this.presentToast('Gagal memverifikasi izin anggota.');
      this.router.navigate(['/collector/savings']);
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
