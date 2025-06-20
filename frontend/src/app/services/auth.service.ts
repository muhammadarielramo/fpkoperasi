import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable, from, firstValueFrom} from 'rxjs';
import { Storage } from '@ionic/storage-angular';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root',
})
export class AuthService {
  private apiUrl = environment.apiUrl;
  private _storage: Storage | null = null;
  private storageReadyPromise: Promise<void>;

  // Mendefinisikan kunci penyimpanan di satu tempat agar konsisten
  private readonly TOKEN_KEY = 'auth_token';
  private readonly ROLE_KEY = 'auth_role';
  private readonly NAME_KEY = 'auth_username';

  constructor(private http: HttpClient, private storage: Storage) {
    this.storageReadyPromise = this.init();
  }

  /**
   * Mengirim permintaan reset password ke API.
   * @param email Email pengguna yang akan direset.
   * @returns Observable dari respons server.
   */
  forgotPassword(email: string): Observable<any> {
    return this.http.post(`${this.apiUrl}/reset-password`, { email });
  }

  /**
   * Menginisialisasi koneksi ke Ionic Storage.
   */
  async init(): Promise<void> {
    if (this._storage) {
      return; // Hindari inisialisasi ulang
    }
    this._storage = await this.storage.create();
  }

  /**
   * Helper pribadi untuk memastikan storage sudah siap sebelum digunakan.
   */
  private async ensureStorageReady(): Promise<void> {
    return this.storageReadyPromise;
  }

  login(credentials: { username: string; password: string }): Observable<any> {
    return from(this.performLogin(credentials));
  }

  private async performLogin(credentials: any): Promise<any> {
    await this.ensureStorageReady(); // Tunggu hingga storage siap

    // Gunakan firstValueFrom (cara modern) untuk mengubah Observable menjadi Promise
    const response = await firstValueFrom(
      this.http.post<any>(`${this.apiUrl}/login`, credentials)
    );

    if (response && response.token && response.user) {
      await this._storage?.set(this.TOKEN_KEY, response.token);
      await this._storage?.set(this.ROLE_KEY, response.user.id_role);
      await this._storage?.set(this.NAME_KEY, response.user.username);
    }
    return response;
  }

  register(data: FormData): Observable<any> {
    return this.http.post(`${this.apiUrl}/register`, data);
  }

  async logout(): Promise<void> {
    await this.ensureStorageReady(); // Tunggu storage siap
    const token = await this.getToken(); // getToken sudah menunggu, jadi tidak perlu await ganda
    if (token) {
      const headers = new HttpHeaders({ Authorization: `Bearer ${token}` });
      // Kirim permintaan logout, tidak perlu menunggu selesai
      firstValueFrom(this.http.post(`${this.apiUrl}/logout`, {}, { headers }));
    }
    // Hapus semua data dari storage
    await this._storage?.remove(this.TOKEN_KEY);
    await this._storage?.remove(this.ROLE_KEY);
    await this._storage?.remove(this.NAME_KEY);
  }

  async getToken(): Promise<string | null> {
    await this.ensureStorageReady();
    return this._storage?.get(this.TOKEN_KEY);
  }

  async getRole(): Promise<string | null> {
    await this.ensureStorageReady();
    return this._storage?.get(this.ROLE_KEY);
  }

  async getUsername(): Promise<string | null> {
    await this.ensureStorageReady();
    return this._storage?.get(this.NAME_KEY);
  }

  async isLoggedIn(): Promise<boolean> {
    await this.ensureStorageReady();
    // Panggil getToken() yang sudah memiliki logic 'await'
    const token = await this.getToken();
    return !!token;
  }
}
