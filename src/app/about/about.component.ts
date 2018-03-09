import {Component} from "@angular/core";
import {Observable} from "rxjs";
import "rxjs/add/observable/from";
import "rxjs/add/operator/switchMap";

@Component({
	template: require("./about.component.html")
})

export class AboutComponent {}