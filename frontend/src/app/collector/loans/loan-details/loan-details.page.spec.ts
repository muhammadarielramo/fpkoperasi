import { ComponentFixture, TestBed } from '@angular/core/testing';
import { LoanDetailsPage } from './loan-details.page';

describe('LoanDetailsPage', () => {
  let component: LoanDetailsPage;
  let fixture: ComponentFixture<LoanDetailsPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(LoanDetailsPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
