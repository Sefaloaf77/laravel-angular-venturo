import { Component, EventEmitter, Input, Output, SimpleChange } from "@angular/core";
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
    
}
