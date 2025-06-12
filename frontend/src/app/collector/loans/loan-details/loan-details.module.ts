import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { LoanDetailsPageRoutingModule } from './loan-details-routing.module';

import { LoanDetailsPage } from './loan-details.page';

import { ComponentsModule } from '../../../components/components/components.module';


@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    LoanDetailsPageRoutingModule,
    ComponentsModule
  ],
  declarations: [LoanDetailsPage]
})
export class LoanDetailsPageModule {}
