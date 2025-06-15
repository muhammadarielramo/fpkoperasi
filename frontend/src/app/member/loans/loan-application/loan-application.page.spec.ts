import { ComponentFixture, TestBed } from '@angular/core/testing';
import { LoanApplicationPage } from './loan-application.page';

describe('LoanApplicationPage', () => {
  let component: LoanApplicationPage;
  let fixture: ComponentFixture<LoanApplicationPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(LoanApplicationPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
