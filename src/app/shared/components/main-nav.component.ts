import {Component, OnInit} from "@angular/core";
import {Status} from "../classes/status";
import {AuthService} from "../services/auth.service";
import {CookieService} from "ng2-cookies";
import {Router} from "@angular/router";
import {VolunteerSignInService} from "../services/volunteer.sign.in.service";
import {OrganizationSignInService} from "../services/organization.sign.in.service";


@Component({template: require("./main-nav.component.html"),
				selector: "navbar"
})

export class NavbarComponent implements OnInit {
			status: Status = null;
			isAuthenticated: any = null;

			constructor(
				private organizationSignInService: OrganizationSignInService,
				private volunteerSignInService: VolunteerSignInService,
				private cookieService: CookieService,
				private authService: AuthService,
				private router: Router
			){}

			ngOnInit(): void {
				this.isAuthenticated = this.authService.loggedIn();
			}


	signOut() : void {
		this.volunteerSignInService.getSignOut()

			.subscribe(status => {
				this.status = status;
				if(status.status === 200) {

					// delete cookies and jwt
					this.cookieService.deleteAll();
					localStorage.clear();

					// send user back home, refresh page
					location.reload();
					this.router.navigate([""]);
					console.log("goodbye");
				}
			});
	}
}



