import { Injectable } from '@angular/core';
import { LandaService } from 'src/app/core/services/landa.service';

@Injectable({
    providedIn: "root",
})
export class PromoService {
    constructor(private landaService: LandaService) {}

    getPromos(arrParameter = {}) {
        return this.landaService.DataGet("/v1/promo", arrParameter);
    }

    getPromoById(id) {
        return this.landaService.DataGet("/v1/promo/" + id);
    }

    createPromo(payload) {
        return this.landaService.DataPost("/v1/promo", payload);
    }

    updatePromo(payload) {
        return this.landaService.DataPut("/v1/promo", payload);
    }

    deletePromo(id) {
        return this.landaService.DataDelete("/v1/promo/" + id);
    }
}
