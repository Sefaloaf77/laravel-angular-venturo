import { Injectable } from "@angular/core";
import { LandaService } from "src/app/core/services/landa.service";

@Injectable({
    providedIn: "root",
})
export class CategoryService {
    constructor(private landaService: LandaService) {}

    getCategories(arrParameter = {}) {
        return this.landaService.DataGet("/v1/categories", arrParameter);
    }

    getCategoryById(id) {
        return this.landaService.DataGet("/v1/categories/" + id);
    }

    createCategory(payload) {
        return this.landaService.DataPost("/v1/categories", payload);
    }

    updateCategory(payload) {
        return this.landaService.DataPut("/v1/categories", payload);
    }

    deleteCategory(id) {
        return this.landaService.DataDelete("/v1/categories/" + id);
    }
}
