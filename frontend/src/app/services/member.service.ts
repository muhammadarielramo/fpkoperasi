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
   * Memperbarui data profil pengguna yang sedang login.
   * Sekarang menerima FormData untuk menangani unggahan file.
   */
  async updateProfile(profileData: FormData): Promise<Observable<any>> {
    // Saat mengirim FormData, jangan atur Content-Type secara manual.
    // Browser akan melakukannya secara otomatis dengan boundary yang benar.
    const token = await this.authService.getToken();
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    
    // Gunakan POST untuk mengirim FormData. Laravel akan menanganinya
    // meskipun rute didefinisikan sebagai PATCH, dengan "method spoofing".
    // Tambahkan _method: 'PATCH' untuk memberitahu Laravel ini adalah request PATCH.
    profileData.append('_method', 'PATCH');
    
    return this.http.post(`${this.apiUrl}/profile/update`, profileData, { headers });
  }

  // Metode lain tetap sama
  async getDashboardData(): Promise<Observable<any>> {
    const headers = await this.createAuthHeader();
    return this.http.get(`${this.apiUrl}/member/dashboard`, { headers });
  }

  async getProfile(): Promise<Observable<any>> {
    const headers = await this.createAuthHeader();
    return this.http.get(`${this.apiUrl}/profile`, { headers });
  }

  private async createAuthHeader(): Promise<HttpHeaders> {
    const token = await this.authService.getToken();
    return new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
  }
}
