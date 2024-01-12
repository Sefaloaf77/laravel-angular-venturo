import { Injectable } from "@angular/core";
import { LandaService } from "src/app/core/services/landa.service";

@Injectable({
    providedIn: "root",
})
export class DashboardService extends LandaService {
    getSummaries() {
        return this.DataGet("/v1/report/total-sales/summaries");
    }

    getTotalPerYear() {
        return this.DataGet("/v1/report/total-sales/year");
    }
    // getTotalPerMonth(arrParameter = {}) {
    //     return this.DataGet("/v1/report/total-sales/month", arrParameter);
    // }
    getTotalPerMonth() {
        return this.DataGet("/v1/report/total-sales/month");
    }
}
