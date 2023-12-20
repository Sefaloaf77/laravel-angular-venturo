import { ComponentFixture, TestBed } from '@angular/core/testing';

import { TestDirectiveComponent } from './test-directive.component';

describe('TestDirectiveComponent', () => {
  let component: TestDirectiveComponent;
  let fixture: ComponentFixture<TestDirectiveComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ TestDirectiveComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(TestDirectiveComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
