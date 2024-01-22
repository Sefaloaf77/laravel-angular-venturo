import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { LandaService } from 'src/app/core/services/landa.service';

@Injectable({
    providedIn: "root",
})
export class DiscountService {
    constructor(private landaService: LandaService) {}

    // getDiscount(arrParameter = {}) {
    //     return this.landaService.DataGet("/v1/discounts", arrParameter);
    // }
    // getDiscountName(arrParameter = {}) {
    //     return this.landaService.DataGet("/v1/discounts/promo", arrParameter);
    // }

    // getDiscountById(id) {
    //     return this.landaService.DataGet("/v1/discounts/" + id);
    // }

    // createDiscount(payload) {
    //     return this.landaService.DataPost("/v1/discounts", payload);
    // }

    // updateDiscount(payload) {
    //     return this.landaService.DataPut("/v1/discounts", payload);
    // }

    // deleteDiscount(id) {
    //     return this.landaService.DataDelete("/v1/discounts/" + id);
    // }

    getDiscount(arrParameter = {}) {
        return this.landaService.DataGet("/v1/discounts", arrParameter);
    }
    getDiscountByCustomer(customerId) {
        return this.landaService.DataGet("/v1/discounts/" + customerId);
    }
    getCustomersByIds(arrParameter = {}) {
        return this.landaService.DataGet("/v1/discounts", arrParameter);
    }

    getTableHeadings(): Observable<string[]> {
        return this.landaService.DataGet(
            "/v1/discounts/table-headings"
        ) as Observable<string[]>;
    }

    getCountIsAvailable(): Observable<string[]> {
        return this.landaService.DataGet(
            "/v1/discounts/count-isavailable"
        ) as Observable<string[]>;
    }

    getDiscountById(id) {
        return this.landaService.DataGet("/v1/discounts/" + id);
    }

    createDiscount(payload) {
        return this.landaService.DataPost("/v1/discounts", payload);
    }

    updateDiscount(payload) {
        return this.landaService.DataPut("/v1/discounts", payload);
    }

    // updateDiscount(discountId, payload) {
    //     return this.landaService.DataPut(
    //         "/v1/discounts/" + discountId,
    //         payload
    //     );
    // }

    updateCustomer(customerId, payload) {
        return this.landaService.DataPut(
            "/v1/customers/" + customerId,
            payload
        );
    }
}
