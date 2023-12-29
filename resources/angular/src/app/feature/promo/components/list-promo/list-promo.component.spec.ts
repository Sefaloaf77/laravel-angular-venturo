import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ListPromoComponent } from './list-promo.component';

describe('ListPromoComponent', () => {
  let component: ListPromoComponent;
  let fixture: ComponentFixture<ListPromoComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ListPromoComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ListPromoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
