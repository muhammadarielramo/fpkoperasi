import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from 'src/environments/environment';
import { AuthService } from './auth.service';

@Injectable({
  providedIn: 'root'
})
export class DepositService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient, private authService: AuthService) { }

  /**
   * Mendapatkan detail simpanan untuk pengguna yang sedang login (untuk Member).
   */
  async getMyDeposits(): Promise<Observable<any>> {
    const headers = await this.createAuthHeader();
    return this.http.get(`${this.apiUrl}/simpanan`, { headers });
  }

  /**
   * Menyimpan setoran simpanan baru untuk seorang member (untuk Collector).
   */
  async saveDeposit(memberId: number, depositData: any): Promise<Observable<any>> {
    const headers = await this.createAuthHeader();
    return this.http.post(`${this.apiUrl}/kolektor/tambah-simpanan/${memberId}`, depositData, { headers });
  }

  private async createAuthHeader(): Promise<HttpHeaders> {
    const token = await this.authService.getToken();
    return new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
  }
}
