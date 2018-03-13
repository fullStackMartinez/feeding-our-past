import {Component, OnInit, ViewChild} from "@angular/core";
import {Observable} from "rxjs/Observable";
import {Router} from "@angular/router";
import {Status} from "../shared/classes/status";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {OrganizationSignUp} from "../shared/classes/organization.sign.up";
import {OrganizationSignUpService} from "../shared/services/organization.sign.up.service";
import {Organization} from "../shared/classes/organization";


//enable jquery $ alias
declare const $: any;

@Component({
	//templateUrl: "./sign-up.html",
	template: require("./organization.sign.up.component.html"),
	selector: "organization-sign-up"
})

export class OrganizationSignUpComponent implements OnInit{

	organizationSignUpForm: FormGroup;
	status: Status = null;

	constructor(
		private formBuilder: FormBuilder,
		private organizationSignUpService: OrganizationSignUpService,
		private router: Router
	){}

	ngOnInit() : void {
		this.organizationSignUpForm = this.formBuilder.group({
			organizationName: ["", [Validators.maxLength(255), Validators.required]],
			organizationEmail: ["", [Validators.maxLength(128), Validators.required]],
			organizationPassword: ["", [Validators.maxLength(128), Validators.required]],
			organizationPasswordConfirm: ["", [Validators.maxLength(128), Validators.required]],
			organizationAddressStreet: ["", [Validators.maxLength(32), Validators.required]],
			organizationAddressCity: ["", [Validators.maxLength(32), Validators.required]],
			organizationAddressState: ["", [Validators.maxLength(32), Validators.required]],
			organizationAddressZip: ["", [Validators.maxLength(32), Validators.required]],
			organizationPhone: ["", [Validators.maxLength(32), Validators.required]],
			organizationHoursOpen: ["", [Validators.maxLength(64), Validators.required]],
			organizationDonationAccepted: ["", [Validators.maxLength(32), Validators.required]],
			organizationUrl: ["", [Validators.maxLength(255)]]
		});
	}


	organizationSignUp() : void {

		let organization: OrganizationSignUp = new OrganizationSignUp(this.organizationSignUpForm.value.organizationAddressCity, this.organizationSignUpForm.value.organizationAddressState, this.organizationSignUpForm.value.organizationAddressStreet, this.organizationSignUpForm.value.organizationAddressZip, this.organizationSignUpForm.value.organizationDonationAccepted, this.organizationSignUpForm.value.organizationEmail, this.organizationSignUpForm.value.organizationHoursOpen, this.organizationSignUpForm.value.organizationName, this.organizationSignUpForm.value.organizationPassword, this.organizationSignUpForm.value.organizationPasswordConfirm, this.organizationSignUpForm.value.organizationPhone, this.organizationSignUpForm.value.organizationUrl);

		this.organizationSignUpService.createOrganization(organization)
			.subscribe(status => {
				this.status = status;

				if(this.status.status === 200) {
					this.organizationSignUpForm.reset();
					console.log("sign-up successful");
					setTimeout(function() {
						alert("Thank you for signing up. Please check your email.");
					}, 500);
					this.router.navigate([""]);
				} else {
					console.log("sign-up fail");
				}
			});
	}
}