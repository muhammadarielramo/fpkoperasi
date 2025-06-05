import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  standalone: false,
  selector: 'app-forgot-password',
  templateUrl: './forgot-password.page.html',
  styleUrls: ['./forgot-password.page.scss'],
})
export class ForgotPasswordPage implements OnInit {
  showPassword = false;
  showPasswordResetConfirm: boolean = false;

  constructor(private router: Router) { }

  ngOnInit() {
  }

    handleResetPassword() {
    // 1. Ambil nilai input Username/NIK
    // const usernameOrNik = ... ; // Dapatkan dari form Anda

    // 2. Panggil service/API untuk mengirim email reset password
    //    Misalnya: this.authService.sendResetPasswordEmail(usernameOrNik).subscribe(
    //      response => {
    //        console.log('Email reset berhasil dikirim:', response);
    //        this.showPasswordResetConfirm = true; // Tampilkan modal konfirmasi
    //      },
    //      error => {
    //        console.error('Gagal mengirim email reset:', error);
    //        // Tampilkan pesan error jika perlu (misalnya dengan Toast atau Alert lain)
    //      }
    //    );

    // Untuk sekarang, kita simulasikan sukses dan tampilkan modal:
    console.log('Tombol Reset Password diklik, menampilkan modal konfirmasi...');
    this.showPasswordResetConfirm = true;
  }
  togglePasswordVisibility() {
    this.showPassword = !this.showPassword;
  }

}
