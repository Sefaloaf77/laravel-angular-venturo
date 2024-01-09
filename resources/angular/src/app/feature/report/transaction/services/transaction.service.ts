import { Injectable } from "@angular/core";
import { LandaService } from "src/app/core/services/landa.service";

@Injectable({
    providedIn: "root",
})
export class TransactionService {
    constructor(private landaService: LandaService) {}

    getSalesTransaction(arrParameter = {}) {
        return this.landaService.DataGet(
            "/v1/report/sales-transaction",
            arrParameter
        );
    }
}
