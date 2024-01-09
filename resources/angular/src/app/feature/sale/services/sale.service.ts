import { Injectable } from '@angular/core';
import { LandaService } from 'src/app/core/services/landa.service';

@Injectable({
    providedIn: "root",
})
export class SaleService {
    constructor(private landaService: LandaService) {}

    getSales(arrParameter = {}) {
        return this.landaService.DataGet("/v1/sale", arrParameter);
    }

    getSaleId(id) {
        return this.landaService.DataGet("/v1/sale/" + id);
    }

    createSale(payload) {
        return this.landaService.DataPost("/v1/sale", payload);
    }

    updateSale(payload) {
        return this.landaService.DataPut("/v1/sale", payload);
    }

    deleteSale(id) {
        return this.landaService.DataDelete("/v1/sale/" + id);
    }
}
