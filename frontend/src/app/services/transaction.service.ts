import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from 'src/environments/environment';
import { AuthService } from './auth.service';

@Injectable({
  providedIn: 'root'
})
export class TransactionService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient, private authService: AuthService) { }

  /**
   * Mengambil riwayat transaksi.
   * @param type (Opsional) Filter berdasarkan tipe: 'loan', 'deposit', 'installment'.
   */
  async getHistory(type?: string): Promise<Observable<any>> {
    const headers = await this.createAuthHeader();
    let params = new HttpParams();
    if (type) {
      params = params.append('type', type);
    }
    return this.http.get(`${this.apiUrl}/riwayat`, { headers, params });
  }

  /**
   * Mengambil detail spesifik dari sebuah transaksi.
   * @param transactionId ID dari transaksi yang akan dilihat detailnya.
   */
  async getDetail(transactionId: number): Promise<Observable<any>> {
    const headers = await this.createAuthHeader();
    const params = new HttpParams().set('id', transactionId.toString());
    return this.http.get(`${this.apiUrl}/riwayat/detail`, { headers, params });
  }

  private async createAuthHeader(): Promise<HttpHeaders> {
    const token = await this.authService.getToken();
    return new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
  }
}
