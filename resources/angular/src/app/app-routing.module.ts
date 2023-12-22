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
];

@NgModule({
    imports: [RouterModule.forRoot(routes, { scrollPositionRestoration: 'top' })],
    exports: [RouterModule]
})

export class AppRoutingModule { }
