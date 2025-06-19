import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from 'src/environments/environment';
import { AuthService } from './auth.service';

@Injectable({
  providedIn: 'root'
})
export class InstallmentService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient, private authService: AuthService) { }

  /**
   * Mendapatkan daftar tagihan angsuran untuk semua anggota binaan (untuk Collector).
   */
  async getLoanInstallments(): Promise<Observable<any>> {
    const headers = await this.createAuthHeader();
    // Mengacu pada endpoint di InstallmentController, bukan di bawah /kolektor
    return this.http.get(`${this.apiUrl}/loan-installments`, { headers });
  }

  /**
   * Mengirim data pembayaran angsuran/setoran (untuk Collector).
   */
  async submitInstallment(loanId: number, installmentData: any): Promise<Observable<any>> {
    const headers = await this.createAuthHeader();
    return this.http.post(`${this.apiUrl}/kolektor/setoran/${loanId}`, installmentData, { headers });
  }

  private async createAuthHeader(): Promise<HttpHeaders> {
    const token = await this.authService.getToken();
    return new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
  }
}
