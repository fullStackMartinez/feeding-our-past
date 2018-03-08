import {Injectable} from "@angular/core";
import {Status} from "../classes/status";
import {Observable} from "rxjs/Observable";
import {OrganizationSignUp} from "../classes/organization.sign.up";
import {HttpClient} from "@angular/common/http";

@Injectable()
export class OrganizationSignUpService {
	constructor(protected http: HttpClient) {

	}

	private organizationSignUpUrl = "api/organization-sign-up/";

	createOrganization(organizationSignUp: OrganizationSignUp) : Observable<Status> {
		return(this.http.post<Status>(this.organizationSignUpUrl, organizationSignUp));
	}
}