import { Component,OnInit } from "@angular/core";
import { UserService } from "../../services/user.service";
import { NgbModal } from "@ng-bootstrap/ng-bootstrap";
import Swal from "sweetalert2";

@Component({
    selector: "app-list-user",
    templateUrl: "./list-user.component.html",
    styleUrls: ["./list-user.component.scss"],
})
export class ListUserComponent {
    listUser: any;
    titleModal: string;
    userId: string;

    constructor(
        private userService: UserService,
        private modalService: NgbModal
    ) {}

    getUser() {
        this.userService.getUsers([]).subscribe(
            (res: any) => {
                this.listUser = res.data.list;
            },
            (err: any) => {
                console.log(err);
            }
        );
    }

    createUser(modalId) {
        this.titleModal = "Tambah User";
        this.userId = "";
        this.modalService.open(modalId, { size: "lg", backdrop: "static" });
    }

    updateUser(modalId, user) {
        this.titleModal = "Edit User: " + user.name;
        this.userId = user.id;
        this.modalService.open(modalId, { size: "lg", backdrop: "static" });
    }

    deleteUser(userId) {
        Swal.fire({
            title: "Apakah kamu yakin ?",
            text: "User ini tidak dapat login setelah kamu menghapus datanya",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#34c38f",
            cancelButtonColor: "#f46a6a",
            confirmButtonText: "Ya, Hapus data ini !",
        }).then((result) => {
            if (!result.value) return false;

            this.userService.deleteUser(userId).subscribe((res: any) => {
                this.getUser();
            });
        });
    }
    ngOnInit(): void {
        this.getUser();
    }
}
