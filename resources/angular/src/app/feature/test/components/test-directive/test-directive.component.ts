import { Component, Input } from "@angular/core";
import { Item } from "src/app/item";
@Component({
    selector: "app-test-directive",
    templateUrl: "./test-directive.component.html",
    styleUrls: ["./test-directive.component.scss"],
})
export class TestDirectiveComponent {
    // @Input() item!: Item;
    item!: Item; // defined to demonstrate template context precedence
    items: Item[] = [
        new Item(0, "Teapot", "stout"),
        new Item(1, "Lamp", "bright"),
        new Item(2, "Phone", "slim"),
        new Item(3, "Television", "vintage"),
        new Item(4, "Fishbowl"),
    ];

    name: string;
    constructor() {}
    ngOnInit(): void {}
}
