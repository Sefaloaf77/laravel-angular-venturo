import {
    Component,
    EventEmitter,
    Input,
    Output,
    ViewChild,
} from "@angular/core";
import { DataTableDirective } from "angular-datatables";
import Swal from "sweetalert2";
import { DiscountService } from "../../services/discount.service";
import { CustomerService } from "src/app/feature/customer/services/customer.service";
import { NgbModal } from "@ng-bootstrap/ng-bootstrap";
import { LandaService } from "src/app/core/services/landa.service";

@Component({
    selector: "app-list-discount",
    templateUrl: "./list-discount.component.html",
    styleUrls: ["./list-discount.component.scss"],
})
export class ListDiscountComponent {
    @ViewChild(DataTableDirective)
    dtElement: DataTableDirective;
    dtInstance: Promise<DataTables.Api>;
    dtOptions: any;

    @Input() promoId: number;
    @Output() afterSave = new EventEmitter<boolean>();

    showLoading: boolean;
    listDiscount: any;
    titleForm: string;
    discountId: number;
    showForm: boolean;
    titleModal: string;
    customerId: number;
    customers: [];
    filter: {
        customer_id: any;
    };

    constructor(
        private discountService: DiscountService,
        private customerService: CustomerService,
        private modalService: NgbModal,
        private landaService: LandaService
    ) {}

    ngOnInit(): void {
        this.showForm = false;
        this.setDefaultFilter();
        this.getDiscount();
        this.getCustomers();
    }

    setDefaultFilter() {
        this.filter = {
            customer_id: "",
        };
    }

    getDiscount() {
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

                this.discountService.getDiscount(params).subscribe(
                    (res: any) => {
                        const { list, meta } = res.data;

                        let number = dtParams.start + 1;
                        list.forEach((val) => (val.no = number++));
                        this.listDiscount = list;

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

    filterByCustomer(customers) {
        let customersId = [];
        customers.forEach((val) => customersId.push(val.id));
        if (!customersId) return false;

        this.filter.customer_id = customersId.join(",");
        this.reloadDataTable();
    }
    formCreate() {
        this.showForm = true;
        this.titleForm = "Tambah Discount";
        this.discountId = 0;
    }

    formUpdate(discount) {
        this.showForm = true;
        this.titleForm = "Edit Discount: " + discount.customer_name;
        this.discountId = discount.id;
    }
    calculateTotal(column: string): number {
        return this.listDiscount.filter((discount) => discount[column] == 1)
            .length;
    }
    updateCustomer(modalId, discount) {
        this.titleModal = "Edit Customer: " + discount.customer_name;
        this.customerId = discount.customer_id;
        this.modalService.open(modalId, { size: "lg", backdrop: "static" });
    }

    onCheckboxChange(
        event: any,
        discountId: number,
        customerId: number,
        promoId: number,
        columnName: string
    ) {
        const existingPayload = {
            id: discountId,
            customer_id: customerId,
            promo_id: promoId,
        };
        const updatedColumnValue = event.target.checked ? 1 : 0;
        const payload = {
            ...existingPayload,
            [columnName]: updatedColumnValue,
        };

        // Panggil fungsi service untuk menyimpan ke database
        this.discountService.updateDiscount(payload).subscribe(
            (res: any) => {
                this.landaService.alertSuccess("Berhasil", res.message);
                this.afterSave.emit();
            },
            (err) => {
                this.landaService.alertError("Mohon Maaf", err.error.errors);
            }
        );
        this.reloadDataTable();
    }
}
