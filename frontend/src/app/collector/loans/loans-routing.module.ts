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
  }

];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class LoansPageRoutingModule {}
