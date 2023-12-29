import {
    Component,
    EventEmitter,
    Input,
    Output,
    SimpleChange,
} from "@angular/core";
import { LandaService } from "src/app/core/services/landa.service";
import * as ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import { LoaderService } from "src/app/core/services/loader.service";
import { PromoService } from "../../services/promo.service";

@Component({
    selector: "app-form-promo",
    templateUrl: "./form-promo.component.html",
    styleUrls: ["./form-promo.component.scss"],
})
export class FormPromoComponent {
    readonly MODE_CREATE = "add";
    readonly MODE_UPDATE = "update";

    @Input() promoId: number;
    @Output() afterSave = new EventEmitter<boolean>();

    configEditor = ClassicEditor;
    activeMode: string;
    promos = [];
    showLoading: boolean;
    formModel: {
        id: number;
        name: string;
        status: string;
        expired_in_day: number;
        nominal_percentage: number;
        nominal_rupiah: number;
        term_conditions: string;
        photo: string;
        photo_url: string;
    };

    constructor(
        private promoService: PromoService,
        private landaService: LandaService
    ) {}

    ngOnInit(): void {}

    ngOnChanges(changes: SimpleChange) {
        this.resetForm();
    }

    getPromos(name = "") {
        this.showLoading = true;
        this.promoService.getPromos({ name: name }).subscribe(
            (res: any) => {
                this.promos = res.data.list;
                this.showLoading = false;
            },
            (err) => {
                console.log(err);
            }
        );
    }

    getCroppedImage($event) {
        this.formModel.photo = $event;
    }

    resetForm() {
        this.getPromos();
        this.formModel = {
            id: 0,
            name: "",
            status: "",
            expired_in_day: 0,
            nominal_percentage: null,
            nominal_rupiah: null,
            term_conditions: "",
            photo: "",
            photo_url: ""
        };

        if (this.promoId > 0) {
            this.activeMode = this.MODE_UPDATE;
            this.getPromo(this.promoId);
            return true;
        }
        this.activeMode = this.MODE_CREATE;
    }

    getPromo(promoId) {
        this.promoService.getPromoById(promoId).subscribe(
            (res: any) => {
                this.formModel = res.data;
            },
            (err) => {
                console.log(err);
            }
        );
    }

    save() {
        switch (this.activeMode) {
            case this.MODE_CREATE:
                this.insert();
                break;
            case this.MODE_UPDATE:
                this.update();
                break;
        }
    }

    insert() {
        this.promoService.createPromo(this.formModel).subscribe(
            (res: any) => {
                this.landaService.alertSuccess("Berhasil", res.message);
                this.afterSave.emit();
            },
            (err) => {
                this.landaService.alertError("Mohon Maaf", err.error.errors);
            }
        );
    }

    update() {
        this.promoService.updatePromo(this.formModel).subscribe(
            (res: any) => {
                this.landaService.alertSuccess("Berhasil", res.message);
                this.afterSave.emit();
            },
            (err) => {
                this.landaService.alertError("Mohon Maaf", err.error.errors);
            }
        );
    }
}
