import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { PerfectScrollbarModule } from 'ngx-perfect-scrollbar';
import { NgbDropdownModule } from '@ng-bootstrap/ng-bootstrap';
import { ClickOutsideModule } from 'ng-click-outside';

import { LayoutComponent } from './layout.component';
import { FooterComponent } from './footer/footer.component';
import { HorizontalComponent } from './horizontal/horizontal.component';
import { HorizontaltopbarComponent } from './horizontaltopbar/horizontaltopbar.component';

@NgModule({
  declarations: [LayoutComponent, FooterComponent, HorizontalComponent, HorizontaltopbarComponent],
  imports: [
    CommonModule,
    RouterModule,
    NgbDropdownModule,
    ClickOutsideModule,
    PerfectScrollbarModule,
  ],
  exports: []
})
export class LayoutsModule { }
