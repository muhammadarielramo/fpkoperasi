import { Location } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ToastController } from '@ionic/angular';
import { CollectorService } from 'src/app/services/collector.service';

@Component({
  standalone: false,
  selector: 'app-member-details',
  templateUrl: './member-details.page.html',
  styleUrls: ['./member-details.page.scss'],
})
export class MemberDetailsPage implements OnInit {
  public member: any = null; // Properti untuk menyimpan semua data member
  public isLoading: boolean = true;

  constructor(
    private location: Location,
    private route: ActivatedRoute,
    private collectorService: CollectorService,
    private toastCtrl: ToastController
  ) {}

  ngOnInit() {
    const memberId = this.route.snapshot.paramMap.get('id');
    if (memberId) {
      this.loadMemberDetails(+memberId);
    }
  }

  async loadMemberDetails(id: number) {
    this.isLoading = true;
    (await this.collectorService.getMemberDetails(id)).subscribe({
      next: (res: any) => {
        if (res && res.data) {
          const memberData = res.data;
          
          // PERBAIKAN: Cek dan ganti http menjadi https untuk photo_url
          if (memberData.photo_url && memberData.photo_url.startsWith('http://')) {
            memberData.photo_url = memberData.photo_url.replace('http://', 'https://');
          }
          
          this.member = memberData;
        }
        this.isLoading = false;
      },
      error: (err: any) => {
        this.isLoading = false;
        this.presentToast('Gagal memuat detail anggota.');
        console.error(err);
      },
    });
  }
  
  goBack() {
    this.location.back();
  }

  async presentToast(message: string) {
    const toast = await this.toastCtrl.create({
      message: message,
      duration: 3000,
      position: 'top',
      color: 'danger',
    });
    await toast.present();
  }
}
