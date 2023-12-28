import { Injectable } from '@angular/core';
import { LandaService } from 'src/app/core/services/landa.service';

@Injectable({
    providedIn: "root",
})
export class ProductService {
    constructor(private landaService: LandaService) {}

    getProducts(arrParameter = {}) {
        return this.landaService.DataGet("/v1/products", arrParameter);
    }

    getProductId(id) {
        return this.landaService.DataGet("/v1/products/" + id);
    }

    createProduct(payload) {
        return this.landaService.DataPost("/v1/products", payload);
    }

    updateProduct(payload) {
        return this.landaService.DataPut("/v1/products", payload);
    }

    deleteProduct(id) {
        return this.landaService.DataDelete("/v1/products/" + id);
    }
}
