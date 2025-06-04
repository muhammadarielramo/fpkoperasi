import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { SavingsPage } from './savings.page';

const routes: Routes = [
  {
    path: '',
    component: SavingsPage
  },  {
    path: 'deposit-savings',
    loadChildren: () => import('./deposit-savings/deposit-savings.module').then( m => m.DepositSavingsPageModule)
  }

];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class SavingsPageRoutingModule {}
