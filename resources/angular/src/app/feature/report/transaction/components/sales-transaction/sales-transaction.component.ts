import { Component, ViewChild } from "@angular/core";

import { TransactionService } from "../../services/transaction.service";
import { CustomerService } from "src/app/feature/customer/services/customer.service";
import { ProductService } from "src/app/feature/product/product/services/product.service";
import { DataTableDirective } from "angular-datatables";

@Component({
    selector: "app-sales-transaction",
    templateUrl: "./sales-transaction.component.html",
    styleUrls: ["./sales-transaction.component.scss"],
})
export class SalesTransactionComponent {
    @ViewChild(DataTableDirective)
    dtElement: DataTableDirective;
    dtInstance: Promise<DataTables.Api>;
    dtOptions: any;

    filter: {
        start_date: string;
        end_date: string;
        customer_id: string;
        product_id: string;
    };
    showLoading: boolean;
    customers: [];
    products: [];
    transactions: any[];

    ngOnInit(): void {
        this.resetFilter();
        this.getCustomers();
        this.getProducts();
        this.reloadTransaction();
    }

    constructor(
        private transactionService: TransactionService,
        private customerService: CustomerService,
        private productService: ProductService
    ) {}

    resetFilter() {
        this.filter = {
            start_date: null,
            end_date: null,
            customer_id: null,
            product_id: null,
        };
    }

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

    getProducts(name = "") {
        this.showLoading = true;
        this.productService.getProducts({ name: name }).subscribe(
            (res: any) => {
                this.products = res.data.list;
                this.showLoading = false;
            },
            (err) => {
                console.log(err);
            }
        );
    }

    reloadTransaction() {
        // this.dtOptions = {
        //     serverSide: true,
        //     processing: true,
        //     ordering: false,
        //     pageLength: 25,
        //     ajax: (dtParams: any, callback) => {
        //         const params = {
        //             ...this.filter,
        //             per_page: dtParams.length,
        //             page: dtParams.start / dtParams.length + 1,
        //         };

        //         this.transactionService.getSalesTransaction(params).subscribe(
        //             (res: any) => {
        //                 const { list, meta } = res.data;

        //                 let number = dtParams.start + 1;
        //                 list.forEach((val) => (val.no = number++));
        //                 this.transactions = list;

        // callback({
        //     recordsTotal: meta.total,
        //     recordsFiltered: meta.total,
        //     data: [],
        // });
        //             },
        //             (err: any) => {}
        //         );
        //     },
        // };
        this.transactionService.getSalesTransaction(this.filter).subscribe(
            (res: any) => {
                const { list } = res.data;
                let number = 1;
                list.forEach((val) => (val.no = number++));
                this.transactions = list;
            },
            (err: any) => {
                console.log(err);
            }
        );
    }

    setFilterPeriod($event) {
        this.filter.start_date = $event.startDate;
        this.filter.end_date = $event.endDate;
        this.reloadTransaction();
    }

    generateSafeParam(list) {
        let paramId = [];
        list.forEach((val) => paramId.push(val.id));
        if (!paramId) return "";

        return paramId.join(",");
    }

    setFilterCustomer(customers) {
        this.filter.customer_id = this.generateSafeParam(customers);
        this.reloadTransaction();
    }

    setFilterProduct(products) {
        this.filter.product_id = this.generateSafeParam(products);
        this.reloadTransaction();
    }
}
