import {Component, OnInit} from "@angular/core";
//import {JwtHelperService} from "@auth0/angular-jwt";
import {Status} from "../shared/classes/status";
import {Organization} from "../shared/classes/organization";
import {OrganizationService} from "../shared/services/organization.service";


import {ActivatedRoute, Params} from "@angular/router";
import {Observable} from "rxjs";
import "rxjs/add/observable/from";
import "rxjs/add/operator/switchMap";
import {LocationService} from "../shared/services/location.service";
import {Point} from "../shared/classes/point";

@Component({
	template: require("./senior.component.html")
})

export class SeniorComponent implements OnInit {
	organizations: Organization [];
	organization: Organization = new Organization(null, null, null, null, null, null, null, null, null, null, null, null, null, null);
	status: Status = null;
	pnt: any;

	constructor(
		private organizationService: OrganizationService,
		private locationService: LocationService
	) {
	}

	ngOnInit(): void {
		this.listOrganizations();
	}

	listOrganizations(): void {
		let location = this.locationService.getCurrentPosition();
		this.organizationService.getOrganizationByDistance(25, location.lat, location.lng)
			.subscribe(organizations=>{
			this.organizations = organizations;
			console.log(this.organization);
		})
	}

	extractPoints(organization:Organization) {
		let point = new Point(organization.organizationLongY, organization.organizationLatX);
		console.log(point);
	}

	clicked({target: marker} : any, organization : Organization) {
		this.organization = marker;
		marker.nguiMapComponent.openInfoWindow('org-details', marker);
	}
	hideMarkerInfo() {
		this.pnt.display = !this.pnt.display;
	}

	displayOrg(organization: Organization) {
		this.organization = organization;
	}
}


