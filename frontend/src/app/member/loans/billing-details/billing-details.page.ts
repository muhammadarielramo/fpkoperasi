import { Location } from '@angular/common';
import { Component, OnInit } from '@angular/core';

@Component({
  standalone: false,
  selector: 'app-billing-details',
  templateUrl: './billing-details.page.html',
  styleUrls: ['./billing-details.page.scss'],
})
export class BillingDetailsPage implements OnInit {

  constructor(private location: Location) { }

  ngOnInit() {
  }

  goBack() {
    this.location.back();
  }

}
