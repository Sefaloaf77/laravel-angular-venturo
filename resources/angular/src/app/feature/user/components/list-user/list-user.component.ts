import { Component, OnInit, ViewChild } from "@angular/core";
import { UserService } from "../../services/user.service";
import { NgbModal } from "@ng-bootstrap/ng-bootstrap";
import Swal from "sweetalert2";
import { DataTableDirective } from "angular-datatables";

@Component({
    selector: "app-list-user",
    templateUrl: "./list-user.component.html",
    styleUrls: ["./list-user.component.scss"],
})
export class ListUserComponent {
    @ViewChild(DataTableDirective)
    dtElement: DataTableDirective;
    dtInstance: Promise<DataTables.Api>;
    dtOptions: any;

    listUser: any;
    titleModal: string;
    userId: string;
    filter: {
        name: "";
    };

    constructor(
        private userService: UserService,
        private modalService: NgbModal
    ) {}

    getUser() {
        this.dtOptions = {
            serverSide: true,
            processing: true,
            ordering: false,
            pageLength: 25,
            ajax: (dtParams: any, callback) => {
                const params = {
                    ...this.filter,
                    per_page: dtParams.length,
                    page: dtParams.start / dtParams.length + 1,
                };

                this.userService.getUsers(params).subscribe(
                    (res: any) => {
                        const { list, meta } = res.data;

                        let number = dtParams.start + 1;
                        list.forEach((val) => (val.no = number++));
                        this.listUser = list;

                        callback({
                            recordsTotal: meta.total,
                            recordsFiltered: meta.total,
                            data: [],
                        });
                    },
                    (err: any) => {}
                );
            },
        };
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
                this.reloadDataTable()
            });
        });
    }

    reloadDataTable(): void {
        this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {
            dtInstance.draw();
        });
    }

    setDefault() {
        this.filter = {
            name: "",
        };
    }

    ngOnInit(): void {
        this.setDefault();
        this.getUser();
    }
}
