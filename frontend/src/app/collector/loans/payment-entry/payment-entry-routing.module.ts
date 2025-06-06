import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { PaymentEntryPage } from './payment-entry.page';

const routes: Routes = [
  {
    path: '',
    component: PaymentEntryPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class PaymentEntryPageRoutingModule {}
