import { Injectable } from '@angular/core';
import { AlertController, Platform } from '@ionic/angular';
import { App } from '@capacitor/app';
import { isPlatform } from '@ionic/angular';
import { MockDetector } from 'capacitor-mock-detector';

@Injectable({
  providedIn: 'root',
})
export class MockLocationService {
  private intervalId: any = null;
  private isAlertShown = false;

  constructor(
    private alertController: AlertController,
    private platform: Platform
  ) {}

  // ---------------------
  // ✅ 1. Check Sekali
  // ---------------------
  async checkMockLocationWithRetry(): Promise<boolean> {
    try {
      const isMockDetected = await this.performMockLocationCheck();
      return isMockDetected;
    } catch (error) {
      console.error('Error during mock location check:', error);
      return false;
    }
  }

  // -------------------------------
  // ✅ 2. Cek Mock Location Berkala
  // -------------------------------
  startContinuousCheck() {
    if (this.intervalId || !isPlatform('android')) return;

    this.intervalId = setInterval(async () => {
      if (this.isAlertShown) return; // Hindari alert berulang

      const isMock = await this.performMockLocationCheck();
      if (isMock) {
        this.isAlertShown = true;
      }
    }, 1000); // setiap 1 detik
  }

  stopContinuousCheck() {
    if (this.intervalId) {
      clearInterval(this.intervalId);
      this.intervalId = null;
      this.isAlertShown = false;
    }
  }

  // ----------------------------------
  // 🔍 3. Pemeriksaan Lokasi Palsu
  // ----------------------------------
  private async performMockLocationCheck(): Promise<boolean> {
    if (!isPlatform('android')) return false;

    try {
      const mockLocationEnabled = await MockDetector.isMockLocationEnabled();
      if (mockLocationEnabled.isEnabled) {
        await this.showMockLocationAlert();
        return true;
      }

      const mockApps = await MockDetector.getMockLocationApps();
      if (mockApps.apps?.length) {
        await this.showMockAppsAlert(mockApps.apps);
        return true;
      }

      const suspicious = await MockDetector.detectSuspiciousLocation();
      if (suspicious.isSuspicious) {
        await this.showMockProviderAlert();
        return true;
      }
    } catch (err) {
      console.warn('Mock location check failed:', err);
    }

    return false;
  }

  // ------------------------------------
  // 🛑 Alert Saat Terjadi Deteksi Palsu
  // ------------------------------------
  private async showMockLocationAlert() {
    const alert = await this.alertController.create({
      header: 'Mock Location Aktif',
      message: 'Nonaktifkan Mock Location di pengaturan Developer.',
      backdropDismiss: false,
      buttons: [
        {
          text: 'Coba Lagi',
          handler: () => {
            this.isAlertShown = false;
            this.checkMockLocationWithRetry();
          },
        },
        {
          text: 'Keluar',
          handler: () => App.exitApp(),
        },
      ],
    });
    await alert.present();
  }

  private async showMockAppsAlert(apps: string[]) {
    const appList = apps.join('\n• ');
    const alert = await this.alertController.create({
      header: 'Aplikasi Mock Terdeteksi',
      message: `Hapus aplikasi berikut:\n\n• ${appList}`,
      backdropDismiss: false,
      buttons: [
        {
          text: 'Coba Lagi',
          handler: () => {
            this.isAlertShown = false;
            this.checkMockLocationWithRetry();
          },
        },
        {
          text: 'Keluar',
          handler: () => App.exitApp(),
        },
      ],
    });
    await alert.present();
  }

  private async showMockProviderAlert() {
    const alert = await this.alertController.create({
      header: 'Lokasi Palsu Terdeteksi',
      message: `Lokasi terdeteksi berasal dari provider palsu.`,
      backdropDismiss: false,
      buttons: [
        {
          text: 'Coba Lagi',
          handler: () => {
            this.isAlertShown = false;
            this.checkMockLocationWithRetry();
          },
        },
        {
          text: 'Keluar',
          handler: () => App.exitApp(),
        },
      ],
    });
    await alert.present();
  }
}
