import { ComponentFixture, TestBed } from '@angular/core/testing';
import { HistoriesPage } from './histories.page';

describe('HistoriesPage', () => {
  let component: HistoriesPage;
  let fixture: ComponentFixture<HistoriesPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(HistoriesPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
