import { Component, EventEmitter, Input, Output, SimpleChange } from "@angular/core";
import { CustomerService } from "../../services/customer.service";
import { LandaService } from "src/app/core/services/landa.service";
import { ProgressServiceService } from "src/app/feature/core/progress-service.service";

@Component({
    selector: "app-form-customer",
    templateUrl: "./form-customer.component.html",
    styleUrls: ["./form-customer.component.scss"],
})
export class FormCustomerComponent {
    @Input() customerId: number;
    @Output() afterSave = new EventEmitter<boolean>();

    readonly MODE_CREATE = "add";
    readonly MODE_UPDATE = "update";

    activeMode: string;
    formModel: {
        photo: string;
        id: number;
        name: string;
        email: string;
        phone_number: string;
        date_of_birth: string;
        is_verified: string;
    };
    isDisabledForm: boolean = false;

    constructor(
        private customerService: CustomerService,
        private landaService: LandaService,
        private progressService: ProgressServiceService
    ) {}

    ngOnInit(): void {}

    ngOnChanges(changes: SimpleChange) {
        this.resetForm();
    }

    resetForm() {
        this.formModel = {
            id: 0,
            name: "",
            email: "",
            phone_number: "",
            date_of_birth: "",
            is_verified: "",
            photo: "",
        };

        if (this.customerId) {
            this.activeMode = this.MODE_UPDATE;
            this.getCustomer(this.customerId);
            return true;
        }
        this.activeMode = this.MODE_CREATE;
    }

    getCustomer(customerId) {
        this.customerService.getCustomerById(customerId).subscribe(
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
        this.isDisabledForm = true;
        this.progressService.startLoading();
        this.customerService.createCustomer(this.formModel).subscribe(
            (res: any) => {
                this.landaService.alertSuccess("Berhasil", res.message);
                this.afterSave.emit();
                this.progressService.finishLoading();
                this.isDisabledForm = false;
            },
            (err) => {
                this.landaService.alertError("Mohon maaf", err.error.errors);
                this.progressService.finishLoading();
                this.isDisabledForm = false;
            }
        );
    }

    update() {
        this.isDisabledForm = true;
        this.progressService.startLoading();
        this.customerService.updateCustomer(this.formModel).subscribe(
            (res: any) => {
                this.landaService.alertSuccess("Berhasil", res.message);
                this.afterSave.emit();
                this.progressService.finishLoading();
                this.isDisabledForm = false;
            },
            (err) => {
                this.landaService.alertError("Mohon maaf", err.error.errors);
                this.progressService.finishLoading();
                this.isDisabledForm = false;
            }
        );
    }

    getCroppedImage($event) {
        this.formModel.photo = $event;
    }
    
}
