import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";
import { DashboardComponent } from "./dashboard/dashboard.component";
import { FormUserComponent } from "./user/components/form-user/form-user.component";
import { TestDirectiveComponent } from "./test/components/test-directive/test-directive.component";
import { ListUserComponent } from "./user/components/list-user/list-user.component";
import { ListCustomerComponent } from "./customer/components/list-customer/list-customer.component";
import { FormProfileComponent } from "./user/components/form-profile/form-profile.component";
import { ListCategoryComponent } from "./product/category/components/list-category/list-category.component";
import { ListProductComponent } from "./product/product/components/list-product/list-product.component";


const routes: Routes = [
    { path: "", redirectTo: "home", pathMatch: "full" },
    { path: "home", component: DashboardComponent },
    {
        path: "user",
        component: ListUserComponent,
    },
    {
        path: "test",
        component: TestDirectiveComponent,
    },
    {
        path: "customer",
        component: ListCustomerComponent,
    },
    {
        path: "editProfile",
        component: FormProfileComponent,
    },
    {
        path: "category",
        component: ListCategoryComponent,
    },
    { path: "product", component:  ListProductComponent},
];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule],
})
export class FeatureRoutingModule {}
