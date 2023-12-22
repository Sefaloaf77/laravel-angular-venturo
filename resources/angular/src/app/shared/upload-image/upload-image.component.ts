import {
    Component,
    EventEmitter,
    Input,
    Output,
    SimpleChange,
} from "@angular/core";
import { NgbModal } from "@ng-bootstrap/ng-bootstrap";
import { ImageCroppedEvent } from "ngx-image-cropper";

@Component({
    selector: "app-upload-image",
    templateUrl: "./upload-image.component.html",
    styleUrls: ["./upload-image.component.scss"],
})
export class UploadImageComponent {
    readonly DEFAULT_RATIO_HIGH = 4;
    readonly DEFAULT_RATIO_WIDTH = 3;
    readonly DEFAULT_IMAGE_QUALITY = 90;

    @Input() ratioHigh: any;
    @Input() ratioWidth: any;
    @Input() imageQuality: any;
    @Input() defaultImage: any;
    @Output() onSubmit = new EventEmitter<any>();

    modalCrop: any;
    imageChangedEvent: any = "";
    croppedImage: any = "";
    imageUrl: string;
    config: {
        ratioHigh: any;
        ratioWidth: any;
        imageQuality: any;
    };

    constructor(private modalService: NgbModal) {}

    ngOnChanges(changes: SimpleChange) {
        this.config = {
            ratioHigh: this.ratioHigh ?? this.DEFAULT_RATIO_HIGH,
            ratioWidth: this.ratioWidth ?? this.DEFAULT_RATIO_WIDTH,
            imageQuality: this.imageQuality ?? this.DEFAULT_IMAGE_QUALITY,
        };

        if (this.defaultImage && this.defaultImage.length > 0) {
            this.imageUrl = this.defaultImage;
        }
    }

    fileChangeEvent(event: any, modalId): void {
        this.imageChangedEvent = event;
        this.modalCrop = this.modalService.open(modalId, {
            size: "lg",
            backdrop: "static",
        });
    }

    imageCropped(event: ImageCroppedEvent) {
        this.croppedImage = event.base64;
    }

    submit() {
        this.imageUrl = this.croppedImage;
        this.onSubmit.emit(this.croppedImage);
        this.modalCrop.close();
    }
}
