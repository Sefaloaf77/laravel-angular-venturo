import { Component, OnInit, AfterViewInit } from '@angular/core';

import { EventService } from '../core/services/event.service';

@Component({
  selector: 'app-layout',
  templateUrl: './layout.component.html',
})

export class LayoutComponent implements OnInit, AfterViewInit {

  // layout related config
  layoutType: string;
  constructor(private eventService: EventService) { }

  ngOnInit() {
    this.layoutType = 'horizontal';
    this.eventService.subscribe('changeLayout', (layout) => {
      this.layoutType = layout;
    });
  }

  ngAfterViewInit(): void {

  }
}
