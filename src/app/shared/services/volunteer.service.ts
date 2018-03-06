import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";

import {Status} from "../classes/status";
import {Volunteer} from "../classes/volunteer";
import {Observable} from "rxjs/Observable";

@Injectable ()
export class VolunteerService  {

	constructor(protected http : HttpClient) {

	}

	//define the API endpoint
	private volunteerUrl = "api/volunteer/";

	//reach out to the volunteer profile  API and delete the volunteer profile in question
	deleteVolunteer(id : number) : Observable<Status> {
		return(this.http.delete<Status>(this.volunteerUrl + id));
	}

	// call to the Volunteer Profile API and edit the volunteer profile in question
	editVolunteer(volunteer: Volunteer) : Observable<Status> {
		return(this.http.put<Status>(this.volunteerUrl , volunteer));
	}

	// call to the Volunteer Profile API and get a Volunteer Profile object by its id
	getVolunteer(id: number) : Observable<Volunteer> {
		return(this.http.get<Volunteer>(this.volunteerUrl + id));

	}

	//call to the volunteer profile API and grab the corresponding volunteer profile by its email
	getVolunteerByVolunteerEmail(volunteerEmail: string) :Observable<Volunteer[]> {
		return(this.http.get<Volunteer[]>(this.volunteerUrl + "?volunteerEmail=" + volunteerEmail));
	}

	// call to the API to grab an array of volunteer profiles based on the user input
	getVolunteerByVolunteerName(volunteerName: string) :Observable<Volunteer[]> {
		return(this.http.get<Volunteer[]>(this.volunteerUrl + "?volunteerName=" + volunteerName));

	}
}