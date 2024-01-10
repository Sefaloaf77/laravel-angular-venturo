import { Component, ViewChild } from "@angular/core";
import { SalesService } from "../../services/sales.service";
import { CategoryService } from "src/app/feature/product/category/services/category.service";
import { LandaService } from "src/app/core/services/landa.service";
import Swal from "sweetalert2";
import { DataTableDirective } from "angular-datatables";

@Component({
    selector: "app-sales-menu",
    templateUrl: "./sales-menu.component.html",
    styleUrls: ["./sales-menu.component.scss"],
})
export class SalesMenuComponent {
    @ViewChild(DataTableDirective)
    dtElement: DataTableDirective;
    dtInstance: Promise<DataTables.Api>;
    dtOptions: any;

    filter: {
        start_date: string;
        end_date: string;
        category_id: string[];
    };
    sales = [
        {
            category_name: "",
            category_total: 0,
            products: [
                {
                    product_name: "",
                    transactions_total: 0,
                    transactions: [{ total_sales: 0 }],
                },
            ],
        },
    ];

    meta: {
        dates: [];
        total_per_date: [];
        grand_total: 0;
    };
    showLoading: boolean;
    categories: [];

    constructor(
        private salesService: SalesService,
        private categoryService: CategoryService,
        private landaService: LandaService
    ) {}

    getCategories(name = "") {
        this.showLoading = true;
        this.categoryService.getCategories({ name: name }).subscribe(
            (res: any) => {
                this.categories = res.data.list;
                this.showLoading = false;
            },
            (err) => {
                console.log(err);
            }
        );
    }

    resetFilter() {
        this.filter = {
            start_date: null,
            end_date: null,
            category_id: null,
        };

        this.meta = {
            dates: [],
            total_per_date: [],
            grand_total: 0,
        };

        this.showLoading = false;
    }

    ngOnInit(): void {
        this.resetFilter();
        this.getCategories();
    }

    filterByPeriode(period) {
        this.filter.start_date = period.startDate;
        this.filter.end_date = period.endDate;
        this.reloadDataTable();
    }

    reloadSales() {
        this.runFilterValidation();

        this.salesService.getSalesMenu(this.filter).subscribe((res: any) => {
            const { data, settings } = res;
            this.sales = data;
            this.meta = settings;
        });
    }

    reloadDataTable(): void {
        this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {
            dtInstance.draw();
        });
    }

    runFilterValidation() {
        if (!this.filter.start_date || !this.filter.end_date) {
            Swal.fire({
                title: "Terjadi Kesalahan",
                text: "Silahkan isi periode penjualan terlebih dahulu",
                icon: "warning",
                showCancelButton: false,
            });
            throw new Error("Start and End date is required");
        }
    }

    generateSafeParam(list) {
        let paramId = [];
        list.forEach((val) => paramId.push(val.id));
        if (!paramId) return "";

        return paramId.join(",");
    }

    setFilterPeriod($event) {
        this.filter.start_date = $event.startDate;
        this.filter.end_date = $event.endDate;
        this.reloadSales();
    }

    setFilterCategory($event) {
        this.filter.category_id = $event.map((category) => category.id); // Update category_id to an array of IDs
        this.reloadSales();
    }

    downloadExcel() {
        this.runFilterValidation();
        let queryParams = {
            start_date: this.filter.start_date,
            end_date: this.filter.end_date,
            category_id: this.filter.category_id,
            is_export_excel: true,
        };
        this.landaService.DownloadLink(
            "/v1/download/sales-category",
            queryParams
        );
    }
}
