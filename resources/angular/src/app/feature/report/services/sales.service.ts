import { Injectable } from "@angular/core";
import { LandaService } from "src/app/core/services/landa.service";

@Injectable({
    providedIn: "root",
})
export class SalesService {
    constructor(private landaService: LandaService) {}

    getSalesPromo(arrParameter = {}) {
        return this.landaService.DataGet(
            "/v1/report/sales-promo",
            arrParameter
        );
    }
    getSalesMenu(arrParameter = {}) {
        return this.landaService.DataGet("/v1/report/sales-menu", arrParameter);
    }
    getSalesCustomer(arrParameter = {}) {
        return this.landaService.DataGet("/v1/report/sales-customer", arrParameter);
    }
}
