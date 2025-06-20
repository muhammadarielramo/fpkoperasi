import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from 'src/environments/environment';
import { AuthService } from './auth.service';

@Injectable({
  providedIn: 'root'
})
export class NotificationService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient, private authService: AuthService) { }

  /**
   * Mengambil semua notifikasi untuk pengguna yang sedang login.
   */
  async getNotifications(): Promise<Observable<any>> {
    const headers = await this.createAuthHeader();
    return this.http.get(`${this.apiUrl}/notifikasi/get`, { headers });
  }

  /**
   * Menandai notifikasi sebagai sudah dibaca.
   */
  async markAsRead(notificationId: number): Promise<Observable<any>> {
    const headers = await this.createAuthHeader();
    return this.http.post(`${this.apiUrl}/notifikasi/read/${notificationId}`, {}, { headers });
  }

  private async createAuthHeader(): Promise<HttpHeaders> {
    const token = await this.authService.getToken();
    return new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
  }
}
