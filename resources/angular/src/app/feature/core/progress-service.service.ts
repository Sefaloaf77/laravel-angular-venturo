import { Injectable } from "@angular/core";

import { NgProgress, NgProgressRef } from "ngx-progressbar";

@Injectable({
    providedIn: "root",
})
export class ProgressServiceService {
    progressRef: NgProgressRef;

    constructor(ngProgress: NgProgress) {
        this.progressRef = ngProgress.ref();
    }

    startLoading() {
        this.progressRef.complete();
        this.progressRef.start();
    }

    finishLoading() {
        this.progressRef.complete();
    }
}
