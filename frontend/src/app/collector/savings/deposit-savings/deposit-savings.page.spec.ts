import { ComponentFixture, TestBed } from '@angular/core/testing';
import { DepositSavingsPage } from './deposit-savings.page';

describe('DepositSavingsPage', () => {
  let component: DepositSavingsPage;
  let fixture: ComponentFixture<DepositSavingsPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(DepositSavingsPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
