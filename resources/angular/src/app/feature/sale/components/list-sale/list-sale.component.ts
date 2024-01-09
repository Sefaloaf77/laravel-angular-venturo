import { Component, ViewChild } from '@angular/core';
import { DataTableDirective } from 'angular-datatables';

@Component({
  selector: 'app-list-sale',
  templateUrl: './list-sale.component.html',
  styleUrls: ['./list-sale.component.scss']
})
export class ListSaleComponent {
  customers:any;
  showLoading:boolean;
  listProduct: any;
  titleForm:string;
  productId:number;
  showForm:boolean;
  filter: {
    customer_id: ""
  }
}
