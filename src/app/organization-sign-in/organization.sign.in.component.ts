import {Component, OnInit} from "@angular/core";
import {router} from "@angular/router";
import {status} from "../..classes/status";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {cookieService} from "ng-cookies";

import {SessionService} from "../..services/session.service";
import {signIn} from "../../classes/sign-in";

//enable jquery $ alias
declare const $: any;

@Component({
		template: require("./organization.sign.in.component.html"),
		selector: "sign-in"
})

export class SignInComponent implements OnInit {

	signInForm: FormGroup:
	signin: Signin = new SignIn(null, null);
	status: Status = null;


	constructor(
		private cookieService: CookieService
		private sessionService: sessionService,
		private formBuilder: FormBuilder,
		private signInService: SignInService,
		private router: Router) {}
		ngOnINIT() : void{
		this.signInForm = this.formBuilder.group({
	profileEmail: ["", [Validators.maxLength(64), Validators.required]],
		});
		this.applyFormChanges();
	}

	applyFormChanges() : void {
		this.signInForm.valueChanges.subscribe(values) => {
	for(let field in values) {
		this.signin[field] = values[field];
			}
		});
}

signIn() : void {
	this.signInService.postSignIn(this.signin)
	.subscribe(status => {
		this.status = status;
		if(this.status.status === 200) {
			this.sessionService.setSession();
			this.signInForm.reset();
			this.router.navigate(["posts"]);
			location.reload();
			console.log("signin successful");
		} else {
			console.log("failed login");
		}
	});
}

signOut() :void {
	this.signInService.getSignOut();
}
}