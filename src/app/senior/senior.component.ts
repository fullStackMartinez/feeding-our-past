import {Component, OnInit} from "@angular/core";
//import {JwtHelperService} from "@auth0/angular-jwt";
import {Status} from "../shared/classes/status";
import {Organization} from "../shared/classes/organization";
import {OrganizationService} from "../shared/services/organization.service";


import {ActivatedRoute, Params} from "@angular/router";
import {Observable} from "rxjs";
import "rxjs/add/observable/from";
import "rxjs/add/operator/switchMap";

@Component({
	template: require("./senior.component.html")
})

export class SeniorComponent implements OnInit {
	organizations: Organization [];
	status: Status = null;

	constructor(
		private organizationService: OrganizationService,
					//		private jwtHelper : JwtHelperService,
) {
	}

	ngOnInit(): void {
		this.listOrganizations();
//		this.currentlySignedIn()
	}

	listOrganizations(): void {
		//TO DO: figure out how to list all organizations
	}

	/*	currentlySignedIn() : void {

			const decodedJwt = this.jwtHelper.decodeToken(localStorage.getItem('jwt-token'));
			this.organizationService.getOrganization(decodedJwt.auth.organizationId)
				.subscribe(organization => this.organization = organization)
		}*/
}
