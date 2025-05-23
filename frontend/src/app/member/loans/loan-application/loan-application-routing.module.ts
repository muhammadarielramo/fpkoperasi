import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { LoanApplicationPage } from './loan-application.page';

const routes: Routes = [
  {
    path: '',
    component: LoanApplicationPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class LoanApplicationPageRoutingModule {}
