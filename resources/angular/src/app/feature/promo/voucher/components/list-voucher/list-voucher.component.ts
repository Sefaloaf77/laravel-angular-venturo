import { Component, ViewChild } from "@angular/core";
import { DataTableDirective } from "angular-datatables";
import { VoucherService } from "../../services/voucher.service";
import { CustomerService } from "src/app/feature/customer/services/customer.service";
import Swal from "sweetalert2";

@Component({
    selector: "app-list-voucher",
    templateUrl: "./list-voucher.component.html",
    styleUrls: ["./list-voucher.component.scss"],
})
export class ListVoucherComponent {
    @ViewChild(DataTableDirective)
    dtElement: DataTableDirective;
    dtInstance: Promise<DataTables.Api>;
    dtOptions: any;

    showLoading: boolean;
    listVoucher: any;
    titleForm: string;
    voucherId: number;
    showForm: boolean;
    customers: [];
    filter: {
        customer_id: any;
        start_time: "";
        end_time: "";
    };

    constructor(
        private voucherService: VoucherService,
        private customerService: CustomerService
    ) {}

    ngOnInit(): void {
        this.showForm = false;
        this.setDefaultFilter();
        this.getVoucher();
        this.getCustomers();
    }

    setDefaultFilter() {
        this.filter = {
            customer_id: "",
            start_time: "",
            end_time: "",
        };
    }

    getVoucher() {
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

                this.voucherService.getVoucher(params).subscribe(
                    (res: any) => {
                        const { list, meta } = res.data;

                        let number = dtParams.start + 1;
                        list.forEach((val) => (val.no = number++));
                        this.listVoucher = list;

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

    getCustomers(name = "") {
        this.showLoading = true;
        this.customerService.getCustomers({ name: name }).subscribe(
            (res: any) => {
                this.customers = res.data.list;
                this.showLoading = false;
            },
            (err) => {
                console.log(err);
            }
        );
    }

    reloadDataTable(): void {
        this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {
            dtInstance.draw();
        });
    }

    filterByPeriode(period) {
        this.filter.start_time = period.startDate;
        this.filter.end_time = period.endDate;
        this.reloadDataTable();
    }

    filterByCustomer(customers) {
        let customersId = [];
        customers.forEach((val) => customersId.push(val.id));
        if (!customersId) return false;

        this.filter.customer_id = customersId.join(",");
        this.reloadDataTable();
    }
    formCreate() {
        this.showForm = true;
        this.titleForm = "Tambah Voucher";
        this.voucherId = 0;
    }

    formUpdate(voucher) {
        this.showForm = true;
        this.titleForm = "Edit Voucher: " + voucher.customer_name;
        this.voucherId = voucher.id;
    }

    deleteVoucher(voucherId) {
        Swal.fire({
            title: "Apakah kamu yakin ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#34c38f",
            cancelButtonColor: "#f46a6a",
            confirmButtonText: "Ya, Hapus data ini !",
        }).then((result) => {
            if (!result.value) return false;

            this.voucherService
                .deleteVoucher(voucherId)
                .subscribe((res: any) => {
                    this.reloadDataTable();
                });
        });
    }
}
