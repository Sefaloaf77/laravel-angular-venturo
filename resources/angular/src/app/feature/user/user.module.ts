import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormUserComponent } from "./components/form-user/form-user.component";
import { FormsModule } from "@angular/forms";
import { ListUserComponent } from "./components/list-user/list-user.component";
import { NgbModule } from "@ng-bootstrap/ng-bootstrap";
import { DataTablesModule } from "angular-datatables";
import { SharedModule } from "src/app/shared/shared.module";
import { FormProfileComponent } from "./components/form-profile/form-profile.component";

@NgModule({
    declarations: [FormUserComponent, ListUserComponent, FormProfileComponent],
    imports: [
        CommonModule,
        FormsModule,
        NgbModule,
        DataTablesModule,
        SharedModule,
    ],
    exports: [FormProfileComponent],
})
export class UserModule {}
