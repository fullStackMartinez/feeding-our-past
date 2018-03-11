import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";

import {Status} from "../classes/status";
import {Organization} from "../classes/organization";
import {Observable} from "rxjs/Observable";

@Injectable ()
export class OrganizationService  {

	constructor(protected http : HttpClient) {

	}

	//define the API endpoint
	private organizationUrl = "api/organization/";

	//reach out to the organization profile API and delete the organization profile in question
	deleteOrganization(id : string) : Observable<Status> {
		return(this.http.delete<Status>(this.organizationUrl + id));
	}

	// call to the Organization Profile API and edit the organization profile in question
	editOrganization(organization: Organization) : Observable<Status> {
		return(this.http.put<Status>(this.organizationUrl , organization));
	}

	// call to the Organization Profile API and get a Organization Profile object by its id
	getOrganization(id: string) : Observable<Organization> {
		return(this.http.get<Organization>(this.organizationUrl + id));

	}

	//call to the organization profile API and grab the corresponding organization profile by its distance
	getOrganizationByDistance(organizationDistance: number, userLatX: number, userLongY: number) :Observable<Organization[]> {
		return(this.http.get<Organization[]>(this.organizationUrl + "?distance=" + organizationDistance + "&userLatX=" + userLatX + "&userLongY=" + userLongY));
	}

	//call to the organization profile API and grab the corresponding organization profile by its email
	getOrganizationByOrganizationEmail(organizationEmail: string) :Observable<Organization[]> {
		return(this.http.get<Organization[]>(this.organizationUrl + "?organizationEmail=" + organizationEmail));
	}

	// call to the organization API to grab an array of organization profiles based on the user input
	getOrganizationByOrganizationName(organizationName: string) :Observable<Organization[]> {
		return(this.http.get<Organization[]>(this.organizationUrl + "?organizationName=" + organizationName));

	}
}