import { Component, ViewChild } from "@angular/core";
import { DataTableDirective } from "angular-datatables";
import { SalesService } from "../../services/sales.service";
import { CustomerService } from "src/app/feature/customer/services/customer.service";
import { LandaService } from "src/app/core/services/landa.service";
import { NgbModal } from "@ng-bootstrap/ng-bootstrap";
import Swal from "sweetalert2";
import { TransactionService } from "../../transaction/services/transaction.service";

@Component({
    selector: "app-sales-customer",
    templateUrl: "./sales-customer.component.html",
    styleUrls: ["./sales-customer.component.scss"],
})
export class SalesCustomerComponent {
    @ViewChild(DataTableDirective)
    dtElement: DataTableDirective;
    dtInstance: Promise<DataTables.Api>;
    dtOptions: any;

    filter: {
        start_date: string;
        end_date: string;
        customer_id: string;
    };
    sales = [
        {
            customer_name: "",
            customer_total: 0,
            transactions: [
                {
                    date_transaction: "",
                    total_sales: 0,
                },
            ],
            transactions_total: 0,
        },
    ];

    meta: {
        dates: [];
        total_per_date: [];
        grand_total: 0;
    };
    showLoading: boolean;
    customers: [];
    titleModal: string;
    customer_id: number;
    customerName: string;
    salesDetail: any;

    constructor(
        private salesService: SalesService,
        private transactionService: TransactionService,
        private customerService: CustomerService,
        private landaService: LandaService,
        private modalService: NgbModal
    ) {}

    getCustomers(name = "") {
        this.showLoading = true;
        this.customerService.getCustomers({ name: name }).subscribe(
            (res: any) => {
                this.customers = res.data.list;
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
            customer_id: null,
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
        this.getCustomers();
    }

    reloadSales() {
        this.runFilterValidation();

        this.salesService
            .getSalesCustomer(this.filter)
            .subscribe((res: any) => {
                const { data, settings } = res;
                this.sales = data;
                this.meta = settings;
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
        // this.reloadSales();
    }

    setFilterCustomer($event) {
        this.filter.customer_id = $event && $event.id ? $event.id : ""; // Update customer_id to an array of IDs
        // this.reloadSales();
    }
    downloadExcel() {
        this.runFilterValidation();
        let queryParams = {
            start_date: this.filter.start_date,
            end_date: this.filter.end_date,
            customer_id: this.filter.customer_id,
            is_export_excel: true,
        };
        this.landaService.DownloadLink(
            "/v1/download/sales-customer",
            queryParams
        );
    }
    formatDate(inputDate) {
        const months = [
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember",
        ];

        const parts = inputDate.split("-");
        const day = parseInt(parts[2], 10);
        const monthIndex = parseInt(parts[1], 10) - 1;
        const year = parseInt(parts[0], 10);

        const formattedDate = `${day} ${months[monthIndex]} ${year}`;

        return formattedDate;
    }
    detailSales(modalId, customer, sale) {
        this.titleModal =
            "Edit Customer: " +
            customer.customer_name +
            " / " +
            this.formatDate(sale.date_transaction);
        this.customer_id = customer.customer_id;
        this.transactionService
            .getSalesTransaction(customer.customer_id)
            .subscribe((res: any) => {
                // console.log(customer);
                const { settings } = res;
                this.salesDetail = res.data.list;
                this.meta = settings;
            });

        this.modalService.open(modalId, { size: "lg", backdrop: "static" });
    }
}
