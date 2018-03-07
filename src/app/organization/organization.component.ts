import {Component, OnInit} from "@angular/core";
//import {JwtHelperService} from "@auth0/angular-jwt";
import {Status} from "../shared/classes/status";
import {Organization} from "../shared/classes/organization";
import {OrganizationService} from "../shared/services/organization.service";
import {PostService} from "../shared/services/post.service";
import {Post} from "../shared/classes/post"

import {ActivatedRoute, Params} from "@angular/router";
import {Observable} from "rxjs";
import "rxjs/add/observable/from";
import "rxjs/add/operator/switchMap";

@Component({
	template: require("./organization.component.html")
})

export class OrganizationComponent implements OnInit {
	posts: Post[] = [];
	organization: Organization = new Organization(null, null, null, null, null, null, null, null, null, null);
	status: Status = null;

	constructor(private organizationService: OrganizationService,
					//		private jwtHelper : JwtHelperService,
					private postService: PostService) {
	}

	ngOnInit(): void {
		this.listPosts();
//		this.currentlySignedIn()
	}

	listPosts(): void {
		this.postService.getPostByPostEndDateTime()
			.subscribe(posts => {
				this.posts = posts;
			});
	}

	/*	currentlySignedIn() : void {

			const decodedJwt = this.jwtHelper.decodeToken(localStorage.getItem('jwt-token'));
			this.organizationService.getOrganization(decodedJwt.auth.organizationId)
				.subscribe(organization => this.organization = organization)
		}*/
}
