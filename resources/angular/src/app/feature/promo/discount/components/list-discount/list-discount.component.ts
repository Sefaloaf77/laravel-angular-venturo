import {
    ChangeDetectorRef,
    Component,
    EventEmitter,
    Input,
    Output,
    ViewChild,
} from "@angular/core";
import { DataTableDirective } from "angular-datatables";
import Swal from "sweetalert2";
import { DiscountService } from "../../services/discount.service";
import { CustomerService } from "src/app/feature/customer/services/customer.service";
import { NgbModal } from "@ng-bootstrap/ng-bootstrap";
import { LandaService } from "src/app/core/services/landa.service";
import { PromoService } from "../../../services/promo.service";

@Component({
    selector: "app-list-discount",
    templateUrl: "./list-discount.component.html",
    styleUrls: ["./list-discount.component.scss"],
})
export class ListDiscountComponent {
    discounts: any;
    promo: any;
    showLoading: boolean;
    listCustomers: any;
    selectedCustomers: any;

    titleForm: string;
    discountId: number;
    showForm: boolean;
    filter: {
        customer_id: string;
        // id: any;
    };
    titleModal: string;
    customerId: any;

    constructor(
        private discountService: DiscountService,
        private customerService: CustomerService,
        private promoService: PromoService,
        private modalService: NgbModal
    ) {}

    ngOnInit(): void {
        this.showForm = false;
        this.setDefaultFilter();
        this.getCustomers();
        this.getDiscountsPromo();
        this.getDiscounts();
    }

    setDefaultFilter() {
        this.filter = {
            customer_id: "",
            // id: "",
        };
    }

    toggleDiscountStatus(customerId: number, promoId: number): void {
        const existingDiscount = this.discounts.find(
            (d) => d.customer_id == customerId && d.promo_id == promoId
        );
        const originalStatus = existingDiscount
            ? existingDiscount.is_available
            : 0;

        const toggleStatus = () => {
            const newStatus = existingDiscount
                ? existingDiscount.is_available === 1
                    ? 0
                    : 1
                : 1;

            if (existingDiscount) {
                // const newStatus = existingDiscount.is_available == 1 ? 0 : 1;
                existingDiscount.is_available = newStatus;
                this.updateDiscountStatus({
                    id: existingDiscount.id,
                    is_available: newStatus,
                });
                this.reloadTable();
            } else {
                const newDiscount = {
                    customer_id: customerId,
                    promo_id: promoId,
                    is_available: 1,
                };
                this.discounts.push(newDiscount);
                this.createDiscount(newDiscount);
                this.reloadTable();
            }

            const successMessage =
                newStatus == 1
                    ? "Diskon berhasil diubah"
                    : "Diskon berhasil diubah";
            Swal.fire({
                icon: "success",
                title: "Berhasil",
                text: successMessage,
            });
        };
        if (existingDiscount) {
            // Jika discount sudah ada, tampilkan konfirmasi Swal
            Swal.fire({
                title: "Konfirmasi",
                text: "Apakah Anda yakin ingin mengubah status diskon?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Ya",
                cancelButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    toggleStatus();
                } else {
                    existingDiscount.is_available = originalStatus;
                }
            });
        } else {
            // Jika discount belum ada, langsung lakukan toggle
            toggleStatus();
        }
    }

    isDiscountApplied(customerId: number, promoId: number): boolean {
        return !!this.discounts.find(
            (d) =>
                d.customer_id == customerId &&
                d.promo_id == promoId &&
                d.is_available == 1
        );
    }

    getDiscountsPromo(status = "diskon") {
        this.promoService.getPromos({ status: status }).subscribe(
            (res: any) => {
                this.promo = res.data.list;
                console.log(this.promo);
            },
            (err) => {
                console.log(err);
            }
        );
    }

    getDiscounts() {
        this.discountService.getDiscount().subscribe(
            (res: any) => {
                this.discounts = res.data.list;
                console.log(this.discounts);
            },
            (err) => {
                console.log(err);
            }
        );
    }
    generateSafeParam(list) {
        let paramId = [];
        list.forEach((val) => paramId.push(val.id));
        if (!paramId) return "";

        return paramId.join(",");
    }
    filterByCustomer(customers) {
        this.filter.customer_id = this.generateSafeParam(customers);
        this.reloadTable();
    }
    // filterByCustomer(customers) {
    //     const customerIdsString = customers.join(",");
    //     this.filter.id = customerIdsString;
    //     this.reloadTable();
    // }

    reloadTable() {
        this.customerService.getCustomers(this.filter).subscribe((res: any) => {
            const { list } = res.data;
            this.listCustomers = list;
            this.showLoading = false;
            console.log(res);
        });
    }

    getCustomers(name = "") {
        this.customerService.getCustomers({ name: name }).subscribe(
            (res: any) => {
                this.listCustomers = res.data.list;
                this.showLoading = false;
            },
            (err) => {
                console.log(err);
            }
        );
    }

    createDiscount(discount: any): void {
        this.discountService.createDiscount(discount).subscribe(
            (res: any) => {
                console.log(res);
            },
            (err) => {
                console.error(err);
            }
        );
    }

    updateDiscountStatus(payload: any): void {
        const existingDiscount = this.discounts.find((d) => d.id == payload.id);

        if (existingDiscount) {
            payload.customer_id = existingDiscount.customer_id;
            payload.promo_id = existingDiscount.promo_id;

            this.discountService.updateDiscount(payload).subscribe(
                (res: any) => {
                    console.log(res);
                },
                (err) => {
                    console.log(err);
                    console.error(err);
                }
            );
        }
    }

    getTotalDiscountStatus(promoId: number, discounts: any[]) {
        let count = 0;
        discounts.forEach((discount) => {
            if (discount.is_available == 1 && discount.promo_id == promoId) {
                count++;
            }
        });
        return count !== 0 ? count : 0;
    }

    updateCustomer(modalId, customer) {
        this.titleModal = "Edit customer: " + customer.name;
        this.customerId = customer.id;
        this.modalService.open(modalId, { size: "lg", backdrop: "static" });
    }
}
