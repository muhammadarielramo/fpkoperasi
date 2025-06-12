import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { PaymentEntryPageRoutingModule } from './payment-entry-routing.module';

import { PaymentEntryPage } from './payment-entry.page';

import { ComponentsModule } from '../../../components/components/components.module';


@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    PaymentEntryPageRoutingModule,
    ComponentsModule
  ],
  declarations: [PaymentEntryPage]
})
export class PaymentEntryPageModule {}
