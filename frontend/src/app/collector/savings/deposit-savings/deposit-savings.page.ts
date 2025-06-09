import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { AlertController } from '@ionic/angular';


@Component({
  standalone: false,
  selector: 'app-deposit-savings',
  templateUrl: './deposit-savings.page.html',
  styleUrls: ['./deposit-savings.page.scss'],
})
export class DepositSavingsPage implements OnInit {
  showDepositSuccess = false;
  showLocationModal: boolean = false;

  constructor(
    private router: Router,
    private alertController: AlertController
  ) { }

  ngOnInit() {
  }

  onDepositSubmissionSuccess() {
    this.showDepositSuccess = true;
    
    // Redirect setelah 2 detik
    setTimeout(() => {
      this.showDepositSuccess = false;
      this.router.navigate(['/collector/loans']);
    }, 2000);
  }
  
 // Methods baru untuk location modal
  openLocationModal() {
    this.showLocationModal = true;
  }

  closeLocationModal() {
    this.showLocationModal = false;
  }

  // Method untuk mengaktifkan lokasi
  enableLocation() {
    this.requestLocationPermission();
  }

  // Method untuk request permission lokasi
  async requestLocationPermission() {
    try {
      // Menggunakan Geolocation API
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          (position) => {
            console.log('Location permission granted');
            console.log('Latitude:', position.coords.latitude);
            console.log('Longitude:', position.coords.longitude);
            
            // Simpan koordinat atau lakukan aksi selanjutnya
            this.handleLocationSuccess(position);
            this.closeLocationModal();
          },
          (error) => {
            console.error('Location permission denied or error:', error);
            this.handleLocationError(error);
          },
          {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
          }
        );
      } else {
        console.error('Geolocation is not supported by this browser.');
        this.showAlert('Geolocation tidak didukung oleh browser ini.');
      }
    } catch (error) {
      console.error('Error requesting location permission:', error);
    }
  }

  // Method untuk menangani sukses mendapatkan lokasi
  handleLocationSuccess(position: GeolocationPosition) {
    const latitude = position.coords.latitude;
    const longitude = position.coords.longitude;
    
    // Implementasi sesuai kebutuhan aplikasi
    console.log('User location:', { latitude, longitude });
    
    // Contoh: simpan ke local storage atau kirim ke server
    localStorage.setItem('userLocation', JSON.stringify({
      latitude,
      longitude,
      timestamp: new Date().toISOString()
    }));

    // Show success message
    this.showAlert('Lokasi berhasil diaktifkan!');
  }

  // Method untuk menangani error lokasi
  handleLocationError(error: GeolocationPositionError) {
    let message = '';
    
    switch (error.code) {
      case error.PERMISSION_DENIED:
        message = 'Izin akses lokasi ditolak oleh pengguna.';
        break;
      case error.POSITION_UNAVAILABLE:
        message = 'Informasi lokasi tidak tersedia.';
        break;
      case error.TIMEOUT:
        message = 'Permintaan lokasi timeout.';
        break;
      default:
        message = 'Terjadi error yang tidak diketahui.';
        break;
    }
    
    this.showAlert(message);
  }

  // Helper method untuk menampilkan alert
  private async showAlert(message: string) {
    // Jika menggunakan Ionic AlertController
    const alert = await this.alertController.create({
      header: 'Informasi',
      message: message,
      buttons: ['OK']
    });

    await alert.present();
  }

  // Alternative: Jika menggunakan Capacitor untuk native features
  /*
  import { Geolocation } from '@capacitor/geolocation';

  async requestLocationPermissionCapacitor() {
    try {
      // Request permission first
      const permission = await Geolocation.requestPermissions();
      
      if (permission.location === 'granted') {
        // Get current position
        const coordinates = await Geolocation.getCurrentPosition({
          enableHighAccuracy: true,
          timeout: 10000
        });
        
        console.log('Current position:', coordinates);
        this.handleLocationSuccess(coordinates);
        this.closeLocationModal();
      } else {
        this.showAlert('Izin lokasi diperlukan untuk melanjutkan.');
      }
    } catch (error) {
      console.error('Error getting location:', error);
      this.showAlert('Gagal mendapatkan lokasi.');
    }
  }
  */

  // Method untuk trigger location modal (bisa dipanggil dari button atau lifecycle)
  checkLocationPermission() {
    // Check if location is already granted
    navigator.permissions.query({ name: 'geolocation' }).then((result) => {
      if (result.state === 'granted') {
        console.log('Location permission already granted');
      } else if (result.state === 'prompt') {
        // Show modal to request permission
        this.openLocationModal();
      } else {
        // Permission denied, show modal to encourage user
        this.openLocationModal();
      }
    }).catch(() => {
      // Fallback: show modal if permissions API not available
      this.openLocationModal();
    });
  }
}
