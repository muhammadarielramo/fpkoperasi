import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { IonicModule } from '@ionic/angular';
import { RouterModule } from '@angular/router'; // <-- Tambahkan ini
import { HeaderComponent } from '../header/header.component'; // <-- Sesuaikan path jika perlu



@NgModule({
  declarations: [
    HeaderComponent
  ],
  imports: [
    CommonModule,
    IonicModule,
    RouterModule
  ],
  exports: [
    HeaderComponent
  ]
})
export class ComponentsModule { }
