import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { DepositSavingsPage } from './deposit-savings.page';

const routes: Routes = [
  {
    path: '',
    component: DepositSavingsPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class DepositSavingsPageRoutingModule {}
