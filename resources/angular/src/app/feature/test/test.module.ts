import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TestDirectiveComponent } from './components/test-directive/test-directive.component';
import { FormsModule } from '@angular/forms';



@NgModule({
  declarations: [
    TestDirectiveComponent
  ],
  imports: [
    CommonModule,
    FormsModule
  ]
})
export class TestModule { }
