import {Component, OnInit} from "@angular/core";
import {JwtHelperService} from "@auth0/angular-jwt";
import {Status} from "../shared/classes/status";
import {Volunteer} from "../shared/classes/volunteer";
import {VolunteerService} from "../shared/services/volunteer.service";
import {PostService} from "../shared/services/post.service";
import {Post} from "../shared/classes/post"

import {ActivatedRoute, Params} from "@angular/router";
import {Observable} from "rxjs";
import "rxjs/add/observable/from";
import "rxjs/add/operator/switchMap";


@Component({
	template:`
	<h1>{{volunteer.volunteerName}}</h1>
	
	`
})

export class VolunteerComponent implements OnInit{
	posts: Post[] = [];
	volunteer: Volunteer = new Volunteer(null,null, null, null, null);
	status: Status = null;

	constructor(
		private volunteerService: VolunteerService,
		private jwtHelper : JwtHelperService,
		private postService: PostService) {}

	ngOnInit(): void {
		this.listPosts();
		this.currentlySignedIn()
	}
	listPosts(): void {
		this.postService.getPostByPostEndDateTime()
			.subscribe(posts =>  {this.posts = posts;
				console.log(this.posts)
			});
	}
	currentlySignedIn() : void {

		const decodedJwt = this.jwtHelper.decodeToken(localStorage.getItem('jwt-token'));
		this.volunteerService.getVolunteer(decodedJwt.auth.volunteerId)
			.subscribe(volunteer => this.volunteer = volunteer)
	}
}