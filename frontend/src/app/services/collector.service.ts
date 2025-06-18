import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from 'src/environments/environment';
import { AuthService } from './auth.service';

@Injectable({
  providedIn: 'root'
})
export class CollectorService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient, private authService: AuthService) { }

  /**
   * Mengambil detail spesifik dari seorang anggota.
   */
  async getMemberDetails(memberId: number): Promise<Observable<any>> {
    const headers = await this.createAuthHeader();
    return this.http.get(`${this.apiUrl}/kolektor/detail-anggota/${memberId}`, { headers });
  }

  /**
   * Mengambil semua anggota binaan.
   * @param searchTerm (Opsional) Kata kunci untuk mencari anggota berdasarkan nama.
   */
  async getCoachedMembers(searchTerm?: string): Promise<Observable<any>> {
    const headers = await this.createAuthHeader();
    let params = new HttpParams();
    if (searchTerm) {
      params = params.append('search', searchTerm);
    }
    return this.http.get(`${this.apiUrl}/kolektor/anggota-binaan`, { headers, params });
  }

  /**
   * Mengambil daftar pinjaman aktif dari anggota binaan.
   */
  async getMemberLoans(): Promise<Observable<any>> {
    const headers = await this.createAuthHeader();
    return this.http.get(`${this.apiUrl}/kolektor/anggota-pinjaman`, { headers });
  }

  /**
   * Mengambil daftar kunjungan yang dijadwalkan hari ini.
   */
  async getTodaysVisits(): Promise<Observable<any>> {
    const headers = await this.createAuthHeader();
    return this.http.get(`${this.apiUrl}/kolektor/kunjungan-hari-ini`, { headers });
  }

  private async createAuthHeader(): Promise<HttpHeaders> {
    const token = await this.authService.getToken();
    return new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
  }
}
