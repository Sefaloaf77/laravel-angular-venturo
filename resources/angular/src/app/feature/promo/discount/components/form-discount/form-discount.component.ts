import { Component, EventEmitter, Input, Output } from "@angular/core";
import { NgbModal } from "@ng-bootstrap/ng-bootstrap";
import { DiscountService } from "../../services/discount.service";
import { CustomerService } from "src/app/feature/customer/services/customer.service";
import { PromoService } from "../../../services/promo.service";
import { LandaService } from "src/app/core/services/landa.service";

@Component({
    selector: "app-form-discount",
    templateUrl: "./form-discount.component.html",
    styleUrls: ["./form-discount.component.scss"],
})
export class FormDiscountComponent {
    readonly PROMO_DISCOUNT = "discount";
    readonly MODE_CREATE = "add";
    readonly MODE_UPDATE = "update";

    @Input() discountId: number;
    @Output() afterSave = new EventEmitter<boolean>();

    titleModal: string;
    customerId: number;
    constructor(
        private modalService: NgbModal,
        private discountService: DiscountService,
        private customerService: CustomerService,
        private promoService: PromoService,
        private landaService: LandaService
    ) {}

    updateCustomer(modalId, discount) {
        this.titleModal = "Edit Customer: " + discount.customer_name;
        this.customerId = discount.customer_id;
        this.modalService.open(modalId, { size: "lg", backdrop: "static" });
    }
}
