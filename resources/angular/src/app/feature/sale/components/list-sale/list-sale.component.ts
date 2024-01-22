import { ChangeDetectorRef, Component, ViewChild } from "@angular/core";
import { DataTableDirective } from "angular-datatables";
import { CustomerService } from "src/app/feature/customer/services/customer.service";
import { ProductService } from "src/app/feature/product/product/services/product.service";
import { SaleService } from "../../services/sale.service";
import { NgbModal } from "@ng-bootstrap/ng-bootstrap";
import { VoucherService } from "src/app/feature/promo/voucher/services/voucher.service";

@Component({
    selector: "app-list-sale",
    templateUrl: "./list-sale.component.html",
    styleUrls: ["./list-sale.component.scss"],
})
export class ListSaleComponent {
    @ViewChild(DataTableDirective)
    dtElement: DataTableDirective;
    dtInstance: Promise<DataTables.Api>;
    dtOptions: any;

    activeMode: string;
    showLoading: boolean;
    titleForm: string;
    showForm: boolean;
    page: number = 1;
    pageSize: number = 10;
    collectionSize: number;

    customers: any;
    products: any;
    listProducts: any;
    sales: any;
    productId: number;
    listDiscount: any;
    listVoucher: any;
    selectedCustomer: any;
    selectedProducts: any[] = [];
    selectedProductsIds: number[] = [];

    filter: {
        customer_id: any;
        product_ids: any[];
    };

    constructor(
        private customerService: CustomerService,
        private productService: ProductService,
        private saleService: SaleService,
        private modalService: NgbModal
    ) {}

    ngOnInit(): void {
        this.showForm = false;
        this.setDefault();
        this.getCustomers();
        this.getProduct();
        this.getProducts();
        this.getSale();
    }

    setDefault() {
        this.filter = {
            customer_id: "",
            product_ids: [],
        };
    }

    resetPage() {
        this.page = 1;
        this.setDefault();
        this.getCustomers();
        this.getProduct();
        this.getSale();
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

    getProducts(name = "") {
        this.showLoading = true;
        this.productService.getProducts({ name: name }).subscribe(
            (res: any) => {
                this.products = res.data.list;
                this.showLoading = false;
            },
            (err) => {
                console.log(err);
            }
        );
    }

    getProduct() {
        this.showLoading = true;
        const selectedProductIds = this.selectedProducts.map(
            (product) => product.id
        );

        // Fetch products excluding the selected ones
        const filteredProductIds = this.filter.product_ids.filter(
            (productId) => !selectedProductIds.includes(productId)
        );

        this.productService
            .getProducts({ product_id: filteredProductIds.join(",") })
            .subscribe(
                (res: any) => {
                    this.listProducts = res.data.list;
                    console.log(this.products);
                    this.showLoading = false;
                },
                (err) => {
                    console.log(err);
                    this.showLoading = false;
                }
            );
    }

    getSale(name = "") {
        this.showLoading = true;
        this.saleService
            .getSales({ customer_id: this.filter.customer_id, name: name })
            .subscribe(
                (res: any) => {
                    this.sales = res.data.list;
                    this.listDiscount = res.data.list[0].discount;
                    this.listVoucher = res.data.list[0].voucher;
                    this.showLoading = false;
                },
                (err) => {
                    console.log(err);
                    this.showLoading = false;
                }
            );
    }

    addToCart(productId: number) {
        const selectedProductIndex = this.selectedProducts.findIndex(
            (product) => product.id === productId
        );
        if (selectedProductIndex !== -1) {
            // If the product is already in the cart, increase its quantity
            this.selectedProducts[selectedProductIndex].quantity =
                (this.selectedProducts[selectedProductIndex].quantity || 0) + 1;
        } else {
            // If the product is not in the cart, add it with a quantity of 1
            const selectedProduct = this.products.find(
                (product) => product.id === productId
            );
            if (selectedProduct) {
                selectedProduct.quantity = 1;
                this.selectedProducts.push(selectedProduct);
            }
        }
        console.log(this.selectedProducts);
    }

    reloadDataTable(): void {
        this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {
            dtInstance.draw();
        });
    }

    filterByCustomer(customers: any[]) {
        let customerIds = customers.map((customer) => customer.id);
        if (customerIds.length === 0) {
            this.filter.customer_id = ""; // Reset filter if no customers selected
            this.selectedCustomer = []; // Clear selected customer
        } else {
            this.filter.customer_id = customerIds.join(",");
            // Assuming the first customer is selected, you may modify this logic as needed
            this.selectedCustomer = customers[0];
        }
        this.getSale();
    }

    filterByProduct(products: any[]) {
        this.selectedProducts = []; // Clear selected products
        this.filter.product_ids = products.map((product) => product.id);
        this.getProduct();
    }

    formUpdate(product) {
        this.showForm = true;
        this.titleForm = "Edit Product: " + product.name;
        this.productId = product.id;
    }
}
