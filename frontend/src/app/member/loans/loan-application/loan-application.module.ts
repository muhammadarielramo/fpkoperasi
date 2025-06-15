import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { LoanApplicationPageRoutingModule } from './loan-application-routing.module';

import { LoanApplicationPage } from './loan-application.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    LoanApplicationPageRoutingModule
  ],
  declarations: [LoanApplicationPage]
})
export class LoanApplicationPageModule {}
