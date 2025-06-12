import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { HistoriesPageRoutingModule } from './histories-routing.module';

import { HistoriesPage } from './histories.page';

import { ComponentsModule } from '../../components/components/components.module';


@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    HistoriesPageRoutingModule,
    ComponentsModule
  ],
  declarations: [HistoriesPage]
})
export class HistoriesPageModule {}
