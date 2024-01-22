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
    @Input() profileId: any;
    @Output() afterSave = new EventEmitter<boolean>();

    formModel: {
        id: any;
        name: string;
        email: string;
        password: string;
        photo: any;
        photo_url: any;
        phone_number: string;
        user_roles_id: string;
    };

    isDisabledForm: boolean = false;
    constructor(
        private userService: UserService,
        private landaService: LandaService
    ) {}

    roles: any;
    getRoles() {
        this.userService.getRoles([]).subscribe(
            (res: any) => {
                this.roles = res.data.list;
            },
            (err) => {
                console.log(err);
            }
        );
    }

    getUser(profileId) {
        console.log(profileId);
        this.userService.getUserById(profileId).subscribe(
            (res: any) => {
                this.formModel = res.data;
                console.log(res);
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
        this.getRoles();
        this.formModel = {
            id: "",
            name: "",
            email: "",
            password: "",
            phone_number: "",
            user_roles_id: "",
            photo: "",
            photo_url: "",
        };

        if (this.profileId != 0) {
            this.getUser(this.profileId);
        }
    }

    update() {
        this.isDisabledForm = true;
        this.userService.updateUser(this.formModel).subscribe(
            (res: any) => {
                this.landaService.alertSuccess("Berhasil", res.message);
                this.afterSave.emit();
                this.isDisabledForm = false;
            },
            (err) => {
                this.landaService.alertError("Mohon Maaf", err.error.errors);
                this.isDisabledForm = false;
            }
        );
    }

    ngOnChanges(changes: SimpleChange) {
        this.resetForm();
    }
    ngOnInit(): void {}
}
