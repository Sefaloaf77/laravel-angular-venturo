import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SalesCustomerComponent } from './sales-customer.component';

describe('SalesCustomerComponent', () => {
  let component: SalesCustomerComponent;
  let fixture: ComponentFixture<SalesCustomerComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ SalesCustomerComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(SalesCustomerComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
