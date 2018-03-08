import {Component, OnInit, ViewChild} from "@angular/core";
import {Observable} from "rxjs/Observable";
import {Router} from "@angular/router";
import {Status} from "../shared/classes/status";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {VolunteerSignUp} from "../shared/classes/volunteer.sign.up";
import {VolunteerSignUpService} from "../shared/services/volunteer.sign.up.service";
import {Organization} from "../shared/classes/organization";


//enable jquery $ alias
declare const $: any;

@Component({
	//templateUrl: "./sign-up.html",
	template: require("./volunteer.sign.up.component.html"),
	selector: "volunteer-sign-up"
})

export class VolunteerSignUpComponent implements OnInit{

	volunteerSignUpForm: FormGroup;
	status: Status = null;

	constructor(
		private formBuilder: FormBuilder,
		private volunteerSignUpService: VolunteerSignUpService,
		private router: Router
	){}

	ngOnInit() : void {
		this.volunteerSignUpForm = this.formBuilder.group({
			volunteerName: ["", [Validators.maxLength(255), Validators.required]],
			volunteerEmail: ["", [Validators.maxLength(128), Validators.required]],
			volunteerPassword: ["", [Validators.maxLength(128), Validators.required]],
			volunteerPasswordConfirm: ["", [Validators.maxLength(128), Validators.required]],
			volunteerPhone: ["", [Validators.maxLength(32), Validators.required]],
			volunteerAvailability: ["", [Validators.maxLength(255)]]
		});
	}


	volunteerSignUp() : void {

		let volunteer: VolunteerSignUp = new VolunteerSignUp(this.volunteerSignUpForm.value.volunteerAvailability, this.volunteerSignUpForm.value.volunteerEmail, this.volunteerSignUpForm.value.volunteerName, this.volunteerSignUpForm.value.volunteerPassword, this.volunteerSignUpForm.value.volunteerPasswordConfirm, this.volunteerSignUpForm.value.volunteerPhone);

		this.volunteerSignUpService.createVolunteer(volunteer)
			.subscribe(status => {
				this.status = status;

				if(this.status.status === 200) {
					this.volunteerSignUpService.createVolunteer(volunteer);
					this.volunteerSignUpForm.reset();
					console.log("sign-up successful");
					this.router.navigate([""]);
				} else {
					console.log("sign-up fail");
				}
			});
	}
}