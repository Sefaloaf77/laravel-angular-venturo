import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { PageTitleComponent } from './page-title/page-title.component';
import { ImageCropperModule } from 'ngx-image-cropper';
import { DaterangepickerComponent } from './daterangepicker/daterangepicker.component';
import { Daterangepicker } from 'ng2-daterangepicker';
import { FormsModule } from '@angular/forms';

@NgModule({
  declarations: [PageTitleComponent, DaterangepickerComponent],
  imports: [
    CommonModule,
    FormsModule,
    ImageCropperModule,
    Daterangepicker
  ],
  exports: [
    PageTitleComponent,
    DaterangepickerComponent
  ]
})
export class SharedModule { }
