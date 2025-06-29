import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule} from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { DepositSavingsPageRoutingModule } from './deposit-savings-routing.module';

import { DepositSavingsPage } from './deposit-savings.page';

import { ComponentsModule } from '../../../components/components/components.module';


@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    DepositSavingsPageRoutingModule,
    ComponentsModule,
    ReactiveFormsModule
  ],
  declarations: [DepositSavingsPage]
})
export class DepositSavingsPageModule {}
