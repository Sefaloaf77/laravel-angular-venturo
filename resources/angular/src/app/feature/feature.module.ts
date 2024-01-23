import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ReactiveFormsModule } from "@angular/forms";
import { NgbAlertModule } from "@ng-bootstrap/ng-bootstrap";
import {
    PERFECT_SCROLLBAR_CONFIG,
    PerfectScrollbarModule,
    PerfectScrollbarConfigInterface,
} from "ngx-perfect-scrollbar";

import { FeatureRoutingModule } from "./feature-routing.module";
import { DashboardComponent } from "./dashboard/dashboard.component";
import { UserModule } from "./user/user.module";
import { TestModule } from "./test/test.module";
import { CustomerModule } from "./customer/customer.module";
import { ChartsModule } from "ng2-charts";
import { Daterangepicker } from "ng2-daterangepicker";
import { SharedModule } from 'src/app/shared/shared.module';
import { PromoModule } from "./promo/promo.module";
import { SaleModule } from "./sale/sale.module";
import { ProductModule } from "./product/product.module";
import { ReportModule } from "./report/report.module";

const DEFAULT_PERFECT_SCROLLBAR_CONFIG: PerfectScrollbarConfigInterface = {
    suppressScrollX: true,
    wheelSpeed: 0.3,
};

@NgModule({
    declarations: [DashboardComponent],
    imports: [
        ReactiveFormsModule,
        NgbAlertModule,
        CommonModule,
        FeatureRoutingModule,
        PerfectScrollbarModule,
        UserModule,
        TestModule,
        CustomerModule,
        ProductModule,
        SaleModule,
        ReportModule,
        ChartsModule,
        Daterangepicker,
        SharedModule,
        PromoModule
    ],
    providers: [
        {
            provide: PERFECT_SCROLLBAR_CONFIG,
            useValue: DEFAULT_PERFECT_SCROLLBAR_CONFIG,
        },
    ],
})
export class FeatureModule {}
