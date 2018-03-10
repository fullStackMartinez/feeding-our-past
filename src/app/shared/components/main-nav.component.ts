import {Component, OnInit} from "@angular/core";
import {Status} from "../classes/status";
import {AuthService} from "../services/auth.service";
import {CookieService} from "ng2-cookies";
import {Router} from "@angular/router";



@Component({template: require("./main-nav.component.html"),
				selector: "navbar"
})

export class NavbarComponent implements OnInit {
			status: Status = null;
			isAuthenticated: any = null;
			organizationUsername: string = null;
			organizationId: string = null;

			constructor(
				//private signInService: SignInService,
				private cookieService: CookieService,
				private authService: AuthService,
				private router: Router
			){}

			ngOnInit(): void {
				this.isAuthenticated = this.authService.loggedIn();
				this.organizationId  = this.getOrganizationId();
				//this.organizationName = this.getOrganizationName();
			}


	signOut() : void {
		this.signInService.getSignOut()

			.subscribe(status => {
				this.status = status;
				if(status.status === 200) {

					this.cookieService.deleteAll();
					localStorage.clear();

					this.router.navigate([""]);
					location.reload();
					console.log("goodbye");
				}
			});
	}



	/*	getOrganizationName() : string {
	if(this.authService.decodeJwt()) {
		return this.authService.decode.Jwt().auth.organizationName;
	} else {
			return ''
	} */





}



