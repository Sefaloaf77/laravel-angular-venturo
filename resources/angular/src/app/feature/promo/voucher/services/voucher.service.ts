import { Injectable } from '@angular/core';
import { LandaService } from 'src/app/core/services/landa.service';

@Injectable({
    providedIn: "root",
})
export class VoucherService {
    constructor(private landaService: LandaService) {}

    getVoucher(arrParameter = {}) {
        return this.landaService.DataGet("/v1/vouchers", arrParameter);
    }

    getVoucherById(id) {
        return this.landaService.DataGet("/v1/vouchers/" + id);
    }

    createVoucher(payload) {
        return this.landaService.DataPost("/v1/vouchers", payload);
    }

    updateVoucher(payload) {
        return this.landaService.DataPut("/v1/vouchers", payload);
    }

    deleteVoucher(id) {
        return this.landaService.DataDelete("/v1/vouchers/" + id);
    }

    getVoucherByCustomer(customerId) {
        return this.landaService.DataGet("/v1/vouchers/cust/" + customerId);
    }
}
