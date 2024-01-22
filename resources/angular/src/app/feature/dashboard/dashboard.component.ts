import { Component, OnInit } from "@angular/core";
import { DashboardService } from "./services/dashboard.service";

@Component({
    selector: "app-dashboard",
    templateUrl: "./dashboard.component.html",
    styleUrls: ["./dashboard.component.scss"],
})
export class DashboardComponent implements OnInit {
    activeButton: string = "";
    defaultMonthButtonOn: boolean = false;
    showLoading: boolean;
    constructor(private dashboardService: DashboardService) {}

    total: {
        today: 0;
        yesterday: 0;
        last_month: 0;
        this_month: 0;
    };
    filter: {
        startDate: string;
        endDate: string;
    };

    ngOnInit(): void {
        this.setDefaultTotal();
        this.getSummaries();
        this.getTotalPerYear();
        this.getTotalPerMonth();
        this.resetFilter();
        this.activeButton = "month";
        this.handleButtonClick(this.activeButton);
    }

    resetFilter() {
        this.filter = {
            startDate: null,
            endDate: null,
        };
        this.showLoading = false;
    }
    setDefaultTotal() {
        this.total = {
            today: 0,
            yesterday: 0,
            last_month: 0,
            this_month: 0,
        };
    }

    public barChartOptions = {
        scaleShowVerticalLines: false,
        responsive: true,
        legend: {
            display: false,
        },
        scales: {
            yAxes: [
                {
                    ticks: {
                        callback: function (value) {
                            return (
                                "Rp " +
                                new Intl.NumberFormat("de-DE").format(value)
                            );
                        },
                    },
                },
            ],
        },
        tooltips: {
            callbacks: {
                label: function (tooltipItem) {
                    return (
                        "Rp " +
                        new Intl.NumberFormat("de-DE").format(
                            tooltipItem.yLabel
                        )
                    );
                },
                labelColor: function () {
                    return {
                        borderColor: "#C7E9ED",
                        backgroundColor: "#C7E9ED",
                    };
                },
                labelTextColor: function () {
                    return "#FFF";
                },
            },
        },
    };
    buttons = [
        { id: "year", label: "Tahun" },
        { id: "month", label: "Bulan" },
    ];
    public barChartLabels = [];
    public barChartData = [{ data: [], label: "false", backgroundColor: "" }];

    getSummaries() {
        this.dashboardService.getSummaries().subscribe(
            (res: any) => {
                this.total = res.data;
            },
            (err: any) => {
                console.log(err);
            }
        );
    }

    getTotalPerYear() {
        this.dashboardService.getTotalPerYear().subscribe(
            (resp: any) => {
                this.barChartLabels = resp.data.label;
                this.barChartData = [
                    {
                        data: resp.data.data,
                        label: "false",
                        backgroundColor: "#C7E9ED",
                    },
                ];
            },
            (err: any) => {
                console.log(err);
            }
        );
    }

    getTotalPerMonth() {
        this.dashboardService.getTotalPerMonth().subscribe(
            (resp: any) => {
                const labelMonth = resp.data.label;
                this.barChartLabels = this.reformatMonth(labelMonth);
                // this.barChartLabels = resp.data.label;
                this.barChartData = [
                    {
                        data: resp.data.data,
                        label: "false",
                        backgroundColor: "#C7E9ED",
                    },
                ];
            },
            (err: any) => {
                console.log(err);
            }
        );
    }

    reformatMonth($labelMonth) {
        const monthNames = [
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember",
        ];

        // Mengonversi label menjadi nama bulan
        const labeledData = $labelMonth.map(
            (label) => monthNames[parseInt(label) - 1]
        );
        return labeledData;
    }

    handleButtonClick(buttonId: string) {
        if (buttonId === "year") {
            this.activeButton = buttonId;
            this.defaultMonthButtonOn = true;
            this.getTotalPerYear();
        } else if (buttonId === "month") {
            this.activeButton = buttonId;
            this.defaultMonthButtonOn = false;
            this.getTotalPerMonth();
        }
    }

    setFilterPeriod($event) {
        this.filter.startDate = $event.startDate;
        this.filter.endDate = $event.endDate;
        this.getTotalPerMonth();
    }
}
