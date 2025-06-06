import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { LoansPage } from './loans.page';

const routes: Routes = [
  {
    path: '',
    component: LoansPage
  },  {
    path: 'loan-details',
    loadChildren: () => import('./loan-details/loan-details.module').then( m => m.LoanDetailsPageModule)
  },
  {
    path: 'payment-entry',
    loadChildren: () => import('./payment-entry/payment-entry.module').then( m => m.PaymentEntryPageModule)
  }

];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class LoansPageRoutingModule {}
