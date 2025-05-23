import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { HistoriesPage } from './histories.page';

const routes: Routes = [
  {
    path: '',
    component: HistoriesPage
  },  {
    path: 'transaction-details',
    loadChildren: () => import('./transaction-details/transaction-details.module').then( m => m.TransactionDetailsPageModule)
  }

];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class HistoriesPageRoutingModule {}
