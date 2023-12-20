import { Component } from '@angular/core';
import { Subject } from 'rxjs';
import { LoaderService } from './core/services/loader.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
})

export class AppComponent {

  constructor(
    private loaderService: LoaderService,
  ) {  }
}
