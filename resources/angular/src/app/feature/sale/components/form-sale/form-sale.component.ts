import { Component, EventEmitter, Input, Output } from "@angular/core";
import { CustomerService } from "src/app/feature/customer/services/customer.service";
import { SaleService } from "../../services/sale.service";
import { NgbModal } from "@ng-bootstrap/ng-bootstrap";
import { LandaService } from "src/app/core/services/landa.service";
import { DndDropEvent } from "ngx-drag-drop";

@Component({
    selector: "app-form-sale",
    templateUrl: "./form-sale.component.html",
    styleUrls: ["./form-sale.component.scss"],
})
export class FormSaleComponent {
    readonly MODE_CREATE = "add";
    readonly MODE_UPDATE = "update";

    @Input() customer: any;
    @Input() listVoucher: any;
    @Input() listDiscount: any;
    @Input() selectedProducts: any[] = [];
    @Output() callback: EventEmitter<any> = new EventEmitter<any>();
    @Output() afterSave: EventEmitter<any> = new EventEmitter<any>();

    activeMode: string;
    showLoading: boolean;
    titleModal: string;
    titleForm: string;
    showForm: boolean;

    customers: any[];
    customerId: number;
    saleId: number;
    discountApplied: number = 0;
    voucherApplied: number = 0;
    selectedDiscount: any;
    selectedVoucher: any;

    ngOnInit(): void {}

    constructor(
        private customerService: CustomerService,
        private saleService: SaleService,
        private modalService: NgbModal,
        private landaService: LandaService
    ) {}

    onDragged(event: any, list: any[], index: number) {
        list.splice(index, 1);
    }
    onDrop(event: DndDropEvent, list: any[]) {
        if (event.dropEffect == "move") {
            let index = event.index;

            if (typeof index == "undefined") {
                index = list.length;
            }
            list.splice(index, 0, event.data);
        }
    }
    toggleDetailSelection(product: any) {
        product.selected = !product.selected; // Toggle the product selection
    }

    formUpdate(modalId, customer) {
        this.titleModal = "Edit Customer: " + customer.name;
        this.customerId = customer.id;
        this.modalService.open(modalId, { size: "lg", backdrop: "static" });
    }

    increaseQuantity(product: any) {
        product.quantity = product.quantity ? product.quantity + 1 : 1;
    }

    decreaseQuantity(product: any) {
        if (product.quantity && product.quantity > 0) {
            product.quantity -= 1; // Decrease the quantity by 1

            // If the quantity reaches 0, remove the item from the cart
            if (product.quantity === 0) {
                const index = this.selectedProducts.findIndex(
                    (p) => p.id === product.id
                );
                if (index !== -1) {
                    this.selectedProducts.splice(index, 1); // Remove the item from the array
                }
            }
        }
    }

    applyDiscount(discountValue: number, discountId: string) {
        if (this.discountApplied !== discountValue) {
            this.discountApplied = discountValue;
            this.selectedDiscount = { id: discountId }; // Assign the selected discount ID
        } else {
            this.discountApplied = 0;
            this.selectedDiscount = null; // Reset selected discount
        }
    }

    applyVoucher(voucherValue: number, voucherId: string) {
        if (this.voucherApplied !== voucherValue) {
            this.voucherApplied = voucherValue;
            this.selectedVoucher = { id: voucherId }; // Assign the selected voucher ID
        } else {
            this.voucherApplied = 0;
            this.selectedVoucher = null; // Reset selected voucher
        }
    }

    getSubtotal(): number {
        let subtotal = 0;
        for (const product of this.selectedProducts) {
            subtotal += product.price * (product.quantity || 1);
        }
        return subtotal;
    }

    getTax(subtotal: number): number {
        const taxRate = 0.11; // 11% tax rate
        return subtotal * taxRate;
    }

    getTotalPayment(subtotal: number): number {
        const tax = this.getTax(subtotal);
        const totalDiscount = subtotal * (this.discountApplied / 100);
        return subtotal + tax - totalDiscount - this.voucherApplied;
    }

    formModel: {
        id: number;
        customer_id: string;
        voucher_id: string;
        voucher_nominal: number;
        discount_id: string;
        details: {
            product_id: string;
            product_detail_id: string;
            total_item: number;
            price: number;
            discount_nominal: number;
        }[];
    };

    addDetail() {
        let val = {
            product_id: "",
            product_detail_id: "",
            total_item: 0,
            price: 0,
            discount_nominal: 0,
        };
        this.formModel.details.push(val);
    }

    save() {
        switch (this.activeMode) {
            case this.MODE_CREATE:
                this.insert();
                break;
        }
    }

    insert() {
        const currentDate = Date.now();

        const details = this.selectedProducts.map((product) => ({
            product_id: product.id,
            product_detail_id: product.product_detail_id || "", // Include product_detail_id
            total_item: product.quantity || 0,
            price: this.getTotalPayment(
                product.price * (product.quantity || 1)
            ),
            discount_nominal: this.discountApplied,
        }));

        const payload = {
            customer_id: this.customer?.id,
            voucher_id: this.selectedVoucher?.id,
            voucher_nominal: this.voucherApplied,
            discount_id: this.selectedDiscount?.id,
            date: currentDate,
            details: details,
        };

        console.log("Payload:", payload);

        this.saleService.createSale(payload).subscribe(
            (res: any) => {
                this.landaService.alertSuccess("Berhasil", res.message);
                this.afterSave.emit(true);
            },
            (err) => {
                this.landaService.alertError("Mohon Maaf", err.error.errors);
            }
        );
    }
}
