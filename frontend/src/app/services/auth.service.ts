import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable, tap } from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class AuthService {
  private apiUrl = 'http://kokita.web.id/api';
  private tokenKey = 'auth_token';
  private roleKey = 'auth_role';

  constructor(private http: HttpClient) {}

  login(credentials: {username: string, password: string}): Observable<any> {
    return this.http.post(`${this.apiUrl}/login`, credentials).pipe(
      tap((response: any) => {
        if (response && response.token && response.user) {
          localStorage.setItem(this.tokenKey, response.token);
          localStorage.setItem(this.roleKey, response.user.id_role); 
        }
      })
    );
  }

  /**
   * Mengirim data registrasi sebagai FormData ke API.
   * @param data - Objek FormData yang berisi semua data pengguna, termasuk file.
   * @returns Observable dari respons server.
   */
  register(data: FormData): Observable<any> {
    // Saat mengirim FormData, Angular/browser akan mengatur header Content-Type secara otomatis.
    return this.http.post(`${this.apiUrl}/register`, data);
  }

  logout(): Observable<any> {
    const token = this.getToken();
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });

    // Panggil API logout di server
    return this.http.post(`${this.apiUrl}/logout`, {}, { headers: headers }).pipe(
      tap(() => {
        // Hapus data lokal HANYA setelah server merespons
        localStorage.removeItem(this.tokenKey);
        localStorage.removeItem(this.roleKey);
      })
    );
  }

  getToken(): string | null {
    return localStorage.getItem(this.tokenKey);
  }

  /**
   * Mengambil peran pengguna dari localStorage.
   * @returns string | null
   */
  getRole(): string | null {
    return localStorage.getItem(this.roleKey);
  }

  isLoggedIn(): boolean {
    // Pengguna dianggap login jika ada token
    return this.getToken() !== null;
  }
}
