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
   * Mengambil data profil untuk pengguna yang sedang login.
   */
  async getProfile(): Promise<Observable<any>> {
    const headers = await this.createAuthHeader();
    return this.http.get(`${this.apiUrl}/profile`, { headers });
  }

  /**
   * Memperbarui data profil pengguna yang sedang login.
   * Menggunakan metode PATCH sesuai dengan routes/api.php.
   */
  async updateProfile(profileData: any): Promise<Observable<any>> {
    const headers = await this.createAuthHeader();
    return this.http.patch(`${this.apiUrl}/profile/update`, profileData, { headers });
  }

  private async createAuthHeader(): Promise<HttpHeaders> {
    const token = await this.authService.getToken();
    return new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
  }
}
