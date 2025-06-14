import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { RouteReuseStrategy } from '@angular/router';
import { FormsModule } from '@angular/forms'; // Diperlukan untuk [(ngModel)]
import { provideHttpClient } from '@angular/common/http'; // Impor untuk HttpClient
import { HttpClient, HttpHeaders } from '@angular/common/http';

import { IonicModule, IonicRouteStrategy } from '@ionic/angular';

import { AppComponent } from './app.component';
import { AppRoutingModule } from './app-routing.module';

@NgModule({
  declarations: [AppComponent],
  imports: [
    BrowserModule,
    FormsModule, // Pastikan ini ada di 'imports'
    IonicModule.forRoot(),
    AppRoutingModule,
  ],
  providers: [
    { provide: RouteReuseStrategy, useClass: IonicRouteStrategy },
    provideHttpClient(), // <-- Letakkan di sini, di dalam array 'providers'
  ],
  bootstrap: [AppComponent],
})
export class AppModule {}
