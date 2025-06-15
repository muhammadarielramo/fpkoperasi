import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { DepositSavingsPageRoutingModule } from './deposit-savings-routing.module';

import { DepositSavingsPage } from './deposit-savings.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    DepositSavingsPageRoutingModule
  ],
  declarations: [DepositSavingsPage]
})
export class DepositSavingsPageModule {}
