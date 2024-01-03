import { ComponentFixture, TestBed } from '@angular/core/testing';

import { FormDiscountComponent } from './form-discount.component';

describe('FormDiscountComponent', () => {
  let component: FormDiscountComponent;
  let fixture: ComponentFixture<FormDiscountComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ FormDiscountComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(FormDiscountComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
