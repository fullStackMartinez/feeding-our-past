import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {Observable} from "rxjs/Observable";
import {Status} from "../classes/status";
import {VolunteerSignIn} from "../classes/volunteer.sign.in";

@Injectable()
export class VolunteerSignInService {
	constructor(protected http : HttpClient) {

	}

	private volunteerSignInUrl = "api/volunteer-sign-in/";
	private volunteerSignOutUrl = "api/sign-out";



	//preform the post to initiate sign in
	postVolunteerSignIn(volunteerSignIn:VolunteerSignIn) : Observable<Status> {
		return(this.http.post<Status>(this.volunteerSignInUrl, volunteerSignIn));
	}

	SignOut() : Observable<Status> {
		return(this.http.get<Status>(this.volunteerSignOutUrl));
	}

}