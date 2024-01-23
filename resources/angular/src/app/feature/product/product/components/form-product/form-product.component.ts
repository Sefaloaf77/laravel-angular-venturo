import {
    Component,
    EventEmitter,
    Input,
    Output,
    SimpleChange,
} from "@angular/core";
import { ProductService } from "../../services/product.service";
import { LandaService } from "src/app/core/services/landa.service";
import * as ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import { LoaderService } from "src/app/core/services/loader.service";
import { CategoryService } from "../../../category/services/category.service";
import { NgbModal } from "@ng-bootstrap/ng-bootstrap";

@Component({
    selector: "app-form-product",
    templateUrl: "./form-product.component.html",
    styleUrls: ["./form-product.component.scss"],
})
export class FormProductComponent {
    readonly DEFAULT_STATUS = "1";
    readonly DEFAULT_TYPE = "Toping";
    readonly MODE_CREATE = "add";
    readonly MODE_UPDATE = "update";

    @Input() productId: number;
    @Output() afterSave = new EventEmitter<boolean>();

    configEditor = ClassicEditor;
    activeMode: string;
    categories: [];
    showLoading: boolean;

    titleModal: string;
    categoryId: number;
    formModel: {
        id: number;
        name: string;
        product_category_id: number;
        price: string;
        description: string;
        photo: string;
        photo_url: string;
        is_available: string;
        details: any;
        details_deleted: any[];
    };

    constructor(
        private productService: ProductService,
        private categoriService: CategoryService,
        private landaService: LandaService,
        private modalService: NgbModal
    ) {}

    ngOnInit(): void {}

    ngOnChanges(changes: SimpleChange) {
        this.resetForm();
    }

    getCategories(name = "") {
        this.showLoading = true;
        this.categoriService.getCategories({ name: name }).subscribe(
            (res: any) => {
                this.categories = res.data.list;
                this.showLoading = false;
            },
            (err) => {
                console.log(err);
            }
        );
    }

    getCroppedImage($event) {
        this.formModel.photo = $event;
    }

    resetForm() {
        this.getCategories();
        this.formModel = {
            id: 0,
            name: "",
            product_category_id: null,
            price: "",
            description: "",
            photo: "",
            photo_url: "",
            is_available: this.DEFAULT_STATUS,
            details: [],
            details_deleted: [],
        };

        if (this.productId > 0) {
            this.activeMode = this.MODE_UPDATE;
            this.getProduct(this.productId);
            return true;
        }

        this.activeMode = this.MODE_CREATE;
    }

    getProduct(productId) {
        this.productService.getProductId(productId).subscribe(
            (res: any) => {
                this.formModel = res.data;
                console.log(res.data);
            },
            (err) => {
                console.log(err);
            }
        );
    }

    save() {
        switch (this.activeMode) {
            case this.MODE_CREATE:
                this.insert();
                break;
            case this.MODE_UPDATE:
                this.update();
                break;
        }
    }

    insert() {
        this.productService.createProduct(this.formModel).subscribe(
            (res: any) => {
                this.landaService.alertSuccess("Berhasil", res.message);
                this.afterSave.emit();
            },
            (err) => {
                this.landaService.alertError("Mohon Maaf", err.error.errors);
            }
        );
    }

    update() {
        console.log(this.formModel);

        this.productService.updateProduct(this.formModel).subscribe(
            (res: any) => {
                this.landaService.alertSuccess("Berhasil", res.message);
                this.afterSave.emit();
            },
            (err) => {
                this.landaService.alertError("Mohon Maaf", err.error.errors);
            }
        );
    }

    addDetail() {
        let val = {
            is_added: true,
            description: "",
            type: this.DEFAULT_TYPE,
            price: 0,
        };
        this.formModel.details.push(val);
    }

    removeDetail(paramIndex: number) {
        const removedDetail = this.formModel.details[paramIndex];
        if (!this.formModel.details_deleted) {
            this.formModel.details_deleted = [];
        }

        if (Array.isArray(this.formModel.details)) {
            this.formModel.details.splice(paramIndex, 1);

            if (removedDetail?.id) {
                this.formModel.details_deleted.push(removedDetail);
            }
        }
    }

    changeDetail(details) {
        if (details?.id) {
            details.is_updated = true;
        }
    }

    createCategory(modalId) {
        this.titleModal = "Tambah Category";
        this.categoryId = 0;
        this.modalService.open(modalId, { size: "lg", backdrop: "static" });
    }
}
