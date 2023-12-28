import {
    Component,
    EventEmitter,
    Input,
    Output,
    SimpleChange,
} from "@angular/core";
import { UserService } from "../../services/user.service";
import { LandaService } from "src/app/core/services/landa.service";
import { ProgressServiceService } from "src/app/feature/core/progress-service.service";

@Component({
    selector: "app-form-profile",
    templateUrl: "./form-profile.component.html",
    styleUrls: ["./form-profile.component.scss"],
})
export class FormProfileComponent {
    @Input() userId: string;
    @Output() afterSave = new EventEmitter<boolean>();

    readonly MODE_CREATE = "add";
    readonly MODE_UPDATE = "update";

    roles = [];

    activeMode: string;
    formModel: {
        photo: string;
        id: string;
        name: string;
        email: string;
        password: string;
        phone_number: string;
        user_roles_id: string;
    };
    isDisabledForm: boolean = false;

    constructor(
        private userService: UserService,
        private landaService: LandaService,
        private progressService: ProgressServiceService
    ) {}

    ngOnInit(): void {}

    ngOnChanges(changes: SimpleChange) {
        this.resetForm();
    }

    getUser(userId) {
        this.userService.getUserById(userId).subscribe(
            (res: any) => {
                this.formModel = res.data;
            },
            (err) => {
                console.log(err);
            }
        );
    }

    resetForm() {
        this.getRoles();
        this.formModel = {
            id: "",
            name: "",
            email: "",
            password: "",
            phone_number: "",
            user_roles_id: "",
            photo: "",
        };

        if (this.userId) {
            this.activeMode = this.MODE_UPDATE;
            this.getUser(this.userId);
            return true;
        }
        this.activeMode = this.MODE_CREATE;
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
        this.userService.createUser(this.formModel).subscribe(
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
        this.userService.updateUser(this.formModel).subscribe(
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

    getRoles() {
        this.userService.getRoles().subscribe(
            (res: any) => {
                this.roles = res.data.list;
            },
            (err) => {
                console.log(err);
            }
        );
    }

    getCroppedImage($event) {
        this.formModel.photo = $event;
    }
}
