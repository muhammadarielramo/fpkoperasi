import { ComponentFixture, TestBed } from '@angular/core/testing';
import { PaymentEntryPage } from './payment-entry.page';

describe('PaymentEntryPage', () => {
  let component: PaymentEntryPage;
  let fixture: ComponentFixture<PaymentEntryPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(PaymentEntryPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
