import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
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
   * Mengambil semua anggota binaan untuk kolektor yang sedang login.
   * @returns Observable dengan daftar anggota.
   */
  async getCoachedMembers(): Promise<Observable<any>> {
    const headers = await this.createAuthHeader();
    return this.http.get(`${this.apiUrl}/kolektor/anggota-binaan`, { headers });
  }

  /**
   * Mengambil daftar pinjaman aktif dari anggota binaan.
   * @returns Observable dengan daftar pinjaman.
   */
  async getMemberLoans(): Promise<Observable<any>> {
    const headers = await this.createAuthHeader();
    return this.http.get(`${this.apiUrl}/kolektor/anggota-pinjaman`, { headers });
  }

  /**
   * Mengambil daftar kunjungan yang dijadwalkan hari ini.
   * @returns Observable dengan daftar kunjungan.
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
