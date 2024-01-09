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


@NgModule({
    declarations: [SalesPromoComponent, SalesTransactionComponent],
    imports: [
        CommonModule,
        FormsModule,
        NgSelectModule,
        SharedModule,
        Daterangepicker,
        DataTablesModule,
        CoreModule
    ],
})
export class ReportModule {}
