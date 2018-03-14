import {Component, OnInit} from "@angular/core";
//import {JwtHelperService} from "@auth0/angular-jwt";
import {OrganizationService} from "../shared/services/organization.service";
import {Organization} from "../shared/classes/organization";
import {Status} from "../shared/classes/status";
import {Volunteer} from "../shared/classes/volunteer";
import {VolunteerService} from "../shared/services/volunteer.service";
import {PostService} from "../shared/services/post.service";
import {Post} from "../shared/classes/post"
import {PostAuthor} from "../shared/classes/post.author"

import {ActivatedRoute, Params} from "@angular/router";
import {Observable} from "rxjs";
import "rxjs/add/observable/from";
import "rxjs/add/operator/switchMap";
import {JwtHelperService} from "@auth0/angular-jwt";
import {LocationService} from "../shared/services/location.service";

@Component({
	template: require("./volunteer.component.html")
})

export class VolunteerComponent implements OnInit{
	posts: PostAuthor[] = [];
	organizations: Organization[] = [];
	organization: Organization;
	volunteer: Volunteer = new Volunteer(null, null, null, null, null);
	status: Status = null;

	constructor(
		private volunteerService: VolunteerService,
		private jwtHelper : JwtHelperService,
		private postService: PostService,
		private organizationService: OrganizationService,
		private locationService: LocationService
	) {}

	ngOnInit(): void {
		this.listPosts();
		this.listOrganizations();
		this.currentlySignedIn()
	}

	listPosts(): void {
		this.postService.getPostByPostEndDateTime()
			.subscribe(posts =>  {this.posts = posts;
			});
	}

	listOrganizations(): void {
		let location = this.locationService.getCurrentPosition();
		this.organizationService.getOrganizationByDistance(25, location.lat, location.lng)
			.subscribe(organizations=>{
				this.organizations = organizations;
				console.log(this.organization);

			})
	}

	currentlySignedIn() : void {

		const decodedJwt = this.jwtHelper.decodeToken(localStorage.getItem('jwt-token'));
		this.volunteerService.getVolunteer(decodedJwt.auth.volunteerId)
			.subscribe(volunteer => this.volunteer = volunteer)
	}
}