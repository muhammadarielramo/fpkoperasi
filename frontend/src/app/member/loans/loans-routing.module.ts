import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { LoansPage } from './loans.page';

const routes: Routes = [
  {
    path: '',
    component: LoansPage
  },  {
    path: 'loan-application',
    loadChildren: () => import('./loan-application/loan-application.module').then( m => m.LoanApplicationPageModule)
  },
  {
    path: 'billing-details',
    loadChildren: () => import('./billing-details/billing-details.module').then( m => m.BillingDetailsPageModule)
  }

];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class LoansPageRoutingModule {}
