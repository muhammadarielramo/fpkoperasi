import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from 'src/environments/environment';
import { AuthService } from './auth.service';

@Injectable({
  providedIn: 'root'
})
export class SlipService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient, private authService: AuthService) { }

  /**
   * Meminta server untuk men-generate slip PDF untuk sebuah transaksi.
   * @param transactionId ID dari transaksi.
   * @returns Observable yang berisi URL ke file PDF yang telah dibuat.
   */
  async generateSlip(transactionId: number): Promise<Observable<any>> {
    const headers = await this.createAuthHeader();
    const params = new HttpParams().set('id', transactionId.toString());
    
    // Endpoint ini tidak memerlukan otentikasi berdasarkan routes/api.php Anda,
    // tetapi mengirimkannya tetap merupakan praktik yang baik.
    return this.http.get(`${this.apiUrl}/unduh/slip`, { headers, params });
  }

  private async createAuthHeader(): Promise<HttpHeaders> {
    const token = await this.authService.getToken();
    return new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
  }
}
