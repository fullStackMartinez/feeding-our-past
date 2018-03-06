import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {Observable} from "rxjs/Observable";
import {Status} from "../classes/status";
import {OrganizationSignIn} from "../classes/organization.sign.in";

@Injectable()
export class OrganizationSignInService {
	constructor(protected http : HttpClient) {

	}

	private organizationSignInUrl = "api/organization-sign-in/";
	private organizationSignOutUrl = "api/sign-out";



	//preform the post to initiate sign in
	postOrganizationSignIn(organizationSignIn:OrganizationSignIn) : Observable<Status> {
		return(this.http.post<Status>(this.organizationSignOutUrl, organizationSignIn));
	}

	organizationSignOut() : Observable<Status> {
		return(this.http.get<Status>(this.organizationSignOutUrl));
	}

}