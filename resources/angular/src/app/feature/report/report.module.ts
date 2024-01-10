import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { NgSelectModule } from '@ng-select/ng-select';
import { SharedModule } from 'src/app/shared/shared.module';
import { SalesPromoComponent } from './components/sales-promo/sales-promo.component';
import { Daterangepicker } from 'ng2-daterangepicker';
import { SalesTransactionComponent } from './transaction/components/sales-transaction/sales-transaction.component';
import { DataTablesModule } from "angular-datatables";
import { CoreModule } from 'src/app/core/core.module';
import { SalesMenuComponent } from './components/sales-menu/sales-menu.component';
import { SalesCustomerComponent } from './components/sales-customer/sales-customer.component';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';


@NgModule({
    declarations: [SalesPromoComponent, SalesTransactionComponent, SalesMenuComponent, SalesCustomerComponent],
    imports: [
        CommonModule,
        FormsModule,
        NgSelectModule,
        SharedModule,
        Daterangepicker,
        DataTablesModule,
        CoreModule,
        NgbModule
    ],
})
export class ReportModule {}
