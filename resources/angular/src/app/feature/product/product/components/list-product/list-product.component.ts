import { Component, OnInit, ViewChild } from "@angular/core";
import { DataTableDirective } from "angular-datatables";
import Swal from "sweetalert2";
import { CategoryService } from "../../../category/services/category.service";

import { ProductService } from "../../services/product.service";

@Component({
    selector: "app-list-product",
    templateUrl: "./list-product.component.html",
    styleUrls: ["./list-product.component.scss"],
})
export class ListProductComponent implements OnInit {
    @ViewChild(DataTableDirective)
    dtElement: DataTableDirective;
    dtInstance: Promise<DataTables.Api>;
    dtOptions: any;

    categories: any;
    showLoading: boolean;
    listProduct: any;
    titleForm: string;
    productId: number;
    showForm: boolean;
    filter: {
        name: "";
        product_category_id: "";
        is_available: "";
    };

    constructor(
        private productService: ProductService,
        private categoryService: CategoryService
    ) {}

    ngOnInit(): void {
        this.showForm = false;
        this.setDefault();
        this.getProducts();
        this.getCategories();
    }

    setDefault() {
        this.filter = {
            name: "",
            product_category_id: null,
            is_available: null,
        };
    }

    getProducts() {
        this.dtOptions = {
            serverSide: true,
            processing: true,
            ordering: false,
            pageLength: 25,
            ajax: (dtParams: any, callback) => {
                const params = {
                    ...this.filter,
                    per_page: dtParams.length,
                    page: dtParams.start / dtParams.length + 1,
                };

                this.productService.getProducts(params).subscribe(
                    (res: any) => {
                        const { list, meta } = res.data;

                        let number = dtParams.start + 1;
                        list.forEach((val) => (val.no = number++));
                        this.listProduct = list;

                        callback({
                            recordsTotal: meta.total,
                            recordsFiltered: meta.total,
                            data: [],
                        });
                    },
                    (err: any) => {}
                );
            },
        };
    }

    getCategories(name = "") {
        this.showLoading = true;
        this.categoryService.getCategories({ name: name }).subscribe(
            (res: any) => {
                this.categories = res.data.list;
                this.showLoading = false;
                this.getProducts();
            },
            (err) => {
                console.log(err);
            }
        );
    }

    reloadDataTable(): void {
        this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {
            dtInstance.draw();
        });
    }

    formCreate() {
        this.showForm = true;
        this.titleForm = "Tambah Product";
        this.productId = 0;
    }

    formUpdate(product) {
        this.showForm = true;
        this.titleForm = "Edit Product: " + product.name;
        this.productId = product.id;
    }

    deleteProduct(productId) {
        Swal.fire({
            title: "Apakah kamu yakin ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#34c38f",
            cancelButtonColor: "#f46a6a",
            confirmButtonText: "Ya, Hapus data ini !",
        }).then((result) => {
            if (!result.value) return false;

            this.productService
                .deleteProduct(productId)
                .subscribe((res: any) => {
                    this.reloadDataTable();
                });
        });
    }
}
