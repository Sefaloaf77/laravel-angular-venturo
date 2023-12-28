import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule } from "@angular/forms";
import { NgbModule } from "@ng-bootstrap/ng-bootstrap";
import { DataTablesModule } from "angular-datatables";
import { NgSelectModule } from "@ng-select/ng-select";
import { SharedModule } from "src/app/shared/shared.module";
import { CoreModule } from "src/app/core/core.module";
import { DndModule } from "ngx-drag-drop";
import { CKEditorModule } from "@ckeditor/ckeditor5-angular";

import { ListProductComponent } from "./product/components/list-product/list-product.component";
import { FormProductComponent } from "./product/components/form-product/form-product.component";
import { FormCategoryComponent } from "./category/components/form-category/form-category.component";
import { ListCategoryComponent } from "./category/components/list-category/list-category.component";

@NgModule({
    declarations: [
        ListProductComponent,
        FormProductComponent,
        FormCategoryComponent,
        ListCategoryComponent,
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
    ],
})
export class ProductModule {}
