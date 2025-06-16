// src/app/guards/role.guard.spec.ts

import { TestBed } from '@angular/core/testing';
import { Router } from '@angular/router';
import { ToastController } from '@ionic/angular';
import { AuthService } from '../services/auth.service';
import { RoleGuard } from './role.guard';

// Buat mock (tiruan) untuk service dan controller
const mockAuthService = {
  // Tambahkan mock untuk fungsi yang digunakan guard di sini
  isLoggedIn: jasmine.createSpy('isLoggedIn'),
  getRole: jasmine.createSpy('getRole'),
};

const mockRouter = {
  navigate: jasmine.createSpy('navigate'),
};

const mockToastController = {
  create: jasmine.createSpy('create').and.returnValue(Promise.resolve({
    present: jasmine.createSpy('present'),
  })),
};

describe('RoleGuard', () => {
  let guard: RoleGuard;

  beforeEach(() => {
    TestBed.configureTestingModule({
      // Sediakan guard dan semua dependensinya di sini
      providers: [
        RoleGuard,
        { provide: AuthService, useValue: mockAuthService },
        { provide: Router, useValue: mockRouter },
        { provide: ToastController, useValue: mockToastController },
      ],
    });
    // Ambil instance dari guard yang sudah dibuat oleh TestBed
    guard = TestBed.inject(RoleGuard);
  });

  it('should be created', () => {
    expect(guard).toBeTruthy();
  });

  // Anda bisa menambahkan tes lain di sini, contohnya:
  // it('should allow access if role matches', async () => { ... });
  // it('should deny access if user is not logged in', async () => { ... });
});