import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from 'src/environments/environment';
import { AuthService } from './auth.service';

@Injectable({
  providedIn: 'root'
})
export class MemberService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient, private authService: AuthService) { }

  /**
   * Mengambil data ringkasan untuk dashboard Member.
   */
  async getDashboardData(): Promise<Observable<any>> {
    const headers = await this.createAuthHeader();
    return this.http.get(`${this.apiUrl}/dashboard`, { headers });
  }

  /**
   * Mengambil data profil untuk pengguna yang sedang login.
   */
  async getProfile(): Promise<Observable<any>> {
    const headers = await this.createAuthHeader();
    return this.http.get(`${this.apiUrl}/profile`, { headers });
  }

  /**
   * Memperbarui data profil pengguna yang sedang login.
   * Menerima FormData untuk menangani unggahan file.
   */
  async updateProfile(profileData: FormData): Promise<Observable<any>> {
    const token = await this.authService.getToken();
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    
    // Memberitahu Laravel ini adalah request PATCH melalui method spoofing.
    profileData.append('_method', 'PATCH');
    
    return this.http.post(`${this.apiUrl}/profile/update`, profileData, { headers });
  }

  private async createAuthHeader(): Promise<HttpHeaders> {
    const token = await this.authService.getToken();
    return new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
  }
}
