import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { TransactionDetailsPageRoutingModule } from './transaction-details-routing.module';

import { TransactionDetailsPage } from './transaction-details.page';

import { ComponentsModule } from '../../../components/components/components.module';


@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    TransactionDetailsPageRoutingModule,
    ComponentsModule
  ],
  declarations: [TransactionDetailsPage]
})
export class TransactionDetailsPageModule {}
