import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { LoanApplicationPageRoutingModule } from './loan-application-routing.module';

import { LoanApplicationPage } from './loan-application.page';

import { ComponentsModule } from '../../../components/components/components.module';


@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    LoanApplicationPageRoutingModule,
    ComponentsModule,
    ReactiveFormsModule
  ],
  declarations: [LoanApplicationPage]
})
export class LoanApplicationPageModule {}
