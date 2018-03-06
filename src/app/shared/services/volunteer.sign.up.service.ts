import {Injectable} from "@angular/core";
import {Status} from "../classes/status";
import {Observable} from "rxjs/Observable";
import {VolunteerSignUp} from "../classes/volunteer.sign.up";
import {HttpClient} from "@angular/common/http";

@Injectable()
export class VolunteerSignUpService {
	constructor(protected http: HttpClient) {

	}

	private volunteerSignUpUrl = "api/volunteer-sign-up/";

	createVolunteer(volunteerSignUp: VolunteerSignUp) : Observable<Status> {
		return(this.http.post<Status>(this.volunteerSignUpUrl, volunteerSignUp));
	}
}