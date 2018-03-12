import { Component } from '@angular/core';

@Component({
	template: require ("./footer.component.html"),
	selector: "fop-footer"
})

export class FooterComponent {
	today: number = Date.now();
}