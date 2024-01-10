import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { LayoutComponent } from './layouts/layout.component';

const routes: Routes = [
    {
        path: "auth",
        loadChildren: () =>
            import("./feature/auth/auth.module").then((m) => m.AuthModule),
    },
    {
        path: "",
        component: LayoutComponent,
        loadChildren: () =>
            import("./feature/feature.module").then((m) => m.FeatureModule),
    },
    {
        path: "",
        component: LayoutComponent,
        loadChildren: () =>
            import("./feature/user/user.module").then((m) => m.UserModule),
    },
    {
        path: "",
        component: LayoutComponent,
        loadChildren: () =>
            import("./feature/test/test.module").then((m) => m.TestModule),
    },
    {
        path: "",
        component: LayoutComponent,
        loadChildren: () =>
            import("./feature/customer/customer.module").then((m) => m.CustomerModule),
    },
    {
        path: "",
        component: LayoutComponent,
        loadChildren: () =>
            import("./feature/product/product.module").then((m) => m.ProductModule),
    },
    {
        path: "",
        component: LayoutComponent,
        loadChildren: () =>
            import("./feature/promo/promo.module").then((m) => m.PromoModule),
    },
    {
        path: "",
        component: LayoutComponent,
        loadChildren: () =>
            import("./feature/sale/sale.module").then((m) => m.SaleModule),
    },
    {
        path: "",
        component: LayoutComponent,
        loadChildren: () =>
            import("./feature/report/report.module").then((m) => m.ReportModule),
    },
];

@NgModule({
    imports: [RouterModule.forRoot(routes, { scrollPositionRestoration: 'top' })],
    exports: [RouterModule]
})

export class AppRoutingModule { }
