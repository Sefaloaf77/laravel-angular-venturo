import { Component } from "@angular/core";
import { SalesService } from "../../services/sales.service";
import { CustomerService } from "src/app/feature/customer/services/customer.service";
import { PromoService } from "src/app/feature/promo/services/promo.service";

@Component({
    selector: "app-sales-promo",
    templateUrl: "./sales-promo.component.html",
    styleUrls: ["./sales-promo.component.scss"],
})
export class SalesPromoComponent {
    filter: {
        start_date: string;
        end_date: string;
        customer_id: string;
        promo_id: string;
    };
    showLoading: boolean;
    customers: [];
    promos: [];
    sales: [
        {
            no: 0;
            customer_name: "";
            date_transaction: "";
            promo_name: "";
        }
    ];
    ngOnInit(): void {
        this.resetFilter();
        this.getCustomers();
        this.getPromos();
    }

    constructor(
        private salesService: SalesService,
        private customerService: CustomerService,
        private promoService: PromoService
    ) {}

    resetFilter() {
        this.filter = {
            start_date: null,
            end_date: null,
            customer_id: null,
            promo_id: null,
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
    getPromos(name = "") {
        this.showLoading = true;
        this.promoService.getPromos({ name: name }).subscribe(
            (res: any) => {
                this.promos = res.data.list;
                this.showLoading = false;
            },
            (err) => {
                console.log(err);
            }
        );
    }

    reloadSales() {
        this.salesService.getSalesPromo(this.filter).subscribe((res: any) => {
            const { data } = res;
            let number = 1;
            data.forEach((val) => (val.no = number++));
            this.sales = data;
        });
    }

    setFilterPeriod($event) {
        this.filter.start_date = $event.startDate;
        this.filter.end_date = $event.endDate;
        this.reloadSales();
    }

    generateSafeParam(list) {
        let paramId = [];
        list.forEach((val) => paramId.push(val.id));
        if (!paramId) return "";

        return paramId.join(",");
    }

    setFilterCustomer(customers) {
        this.filter.customer_id = this.generateSafeParam(customers);
        this.reloadSales();
    }

    setFilterPromo(promos) {
        this.filter.promo_id = this.generateSafeParam(promos);
        this.reloadSales();
    }
}
