import {Component, OnInit} from "@angular/core";
import {CookieService} from "ng2-cookies";
import {Router} from "@angular/router";
import {Status} from "../shared/classes/status";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {SessionService} from "../shared/services/session.service";
import {VolunteerSignInService} from "../shared/services/volunteer.sign.in.service";
import {VolunteerSignIn} from "../shared/classes/volunteer.sign.in";

//enable jquery $ alias
declare const $: any;

@Component({
	template: require("./volunteer.sign.in.component.html"),
	selector: "volunteer-sign-in"
})

export class VolunteerSignInComponent implements OnInit {

	volunteerSignInForm: FormGroup;
	status: Status = null;


	constructor(
		private cookieService: CookieService,
		private sessionService: SessionService,
		private formBuilder: FormBuilder,
		private volunteerSignInService: VolunteerSignInService,
		private router: Router) {
	}

	ngOnInit(): void {
		this.volunteerSignInForm = this.formBuilder.group({
			volunteerEmail: ["", [Validators.maxLength(128), Validators.required]],
			volunteerPassword: ["", [Validators.maxLength(128), Validators.required]]
		});
	}

	volunteerSignIn() : void {

		let volunteer: VolunteerSignIn = new VolunteerSignIn(this.volunteerSignInForm.value.volunteerEmail, this.volunteerSignInForm.value.volunteerPassword);

		this.volunteerSignInService.postVolunteerSignIn(volunteer)
			.subscribe(status => {
				this.status = status;
				if(this.status.status === 200) {
					this.sessionService.setSession();
					this.volunteerSignInForm.reset();
					this.router.navigate(["volunteer"]);
					location.reload();
					console.log("sign in successful");
				} else {
					console.log("failed login");
				}
			});
	}

/*	signOut() :void {
		this.volunteerSignInService.getSignOut();
	}*/


}