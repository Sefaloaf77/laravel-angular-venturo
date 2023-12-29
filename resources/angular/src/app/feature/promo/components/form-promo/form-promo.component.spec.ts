import { ComponentFixture, TestBed } from '@angular/core/testing';

import { FormPromoComponent } from './form-promo.component';

describe('FormPromoComponent', () => {
  let component: FormPromoComponent;
  let fixture: ComponentFixture<FormPromoComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ FormPromoComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(FormPromoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
