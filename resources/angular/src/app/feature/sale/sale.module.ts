import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { DataTablesModule } from 'angular-datatables';
import { NgSelectModule } from '@ng-select/ng-select';
import { SharedModule } from 'src/app/shared/shared.module';
import { CoreModule } from 'src/app/core/core.module';
import { CKEditorModule } from '@ckeditor/ckeditor5-angular';
import { DndModule } from 'ngx-drag-drop';
import { ListSaleComponent } from './components/list-sale/list-sale.component';
import { FormSaleComponent } from './components/form-sale/form-sale.component';



@NgModule({
  declarations: [
    ListSaleComponent,
    FormSaleComponent
  ],
  imports: [
    CommonModule,
    FormsModule,
    NgbModule,
    DataTablesModule,
    NgSelectModule,
    SharedModule,
    CoreModule,
    CKEditorModule,
    DndModule
  ]
})
export class SaleModule { }
