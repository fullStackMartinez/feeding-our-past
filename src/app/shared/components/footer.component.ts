import { Component } from '@angular/core';

@Component({
	selector: 'app-components-footer',
	templateUrl: './footer.component.html'
})
export class FooterComponent {
	today: number = Date.now();
}