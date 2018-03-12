import {Component, OnInit} from "@angular/core";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {Status} from "../shared/classes/status";
import {Organization} from "../shared/classes/organization";
import {OrganizationService} from "../shared/services/organization.service";
import {PostService} from "../shared/services/post.service";
import {Post} from "../shared/classes/post";
import {PostAuthor} from "../shared/classes/post.author";
import {AuthService} from "../shared/services/auth.service";
import {ActivatedRoute, Params} from "@angular/router";
import {Observable} from "rxjs";
import "rxjs/add/observable/from";
import "rxjs/add/operator/switchMap";
import {JwtHelperService} from "@auth0/angular-jwt";

@Component({
	template: require("./organization.component.html")
})

export class OrganizationComponent implements OnInit {

	createPostForm: FormGroup;
	posts: PostAuthor[] = [];
	newpost: Post[] = [];
	organization: Organization = new Organization(null, null, null, null, null, null, null, null, null, null, null, null, null, null );
	status: Status = null;

	constructor(
		private formBuilder: FormBuilder,
		private authService: AuthService,
		private organizationService: OrganizationService,
		private jwtHelper : JwtHelperService,
		private postService: PostService) {
	}

	ngOnInit(): void {
		this.listPosts();
		this.currentlySignedIn();

		this.createPostForm = this.formBuilder.group({
			postTitle: ["", [Validators.maxLength(255), Validators.required]],
			postStartDateTime: ["", [Validators.maxLength(32), Validators.required]],
			postEndDateTime: ["", [Validators.maxLength(32), Validators.required]],
			postContent: ["", [Validators.maxLength(4096), Validators.required]]
		})
	}

	listPosts(): void {
		this.postService.getPostByPostEndDateTime()
			.subscribe(posts => {
				this.posts = posts;
			});
	}

	getJwtProfileId() : any {
		if(this.authService.decodeJwt()) {
			return this.authService.decodeJwt().auth.organizationId;
		} else {
			return false
		}
	}

	createPost() {

		// if no JWT organizationId, return false (must be logged in to post)
		if(!this.getJwtProfileId()) {
			return false;
		}

		// grab organizationId off of JWT
		let newPostOrganizationId = this.getJwtProfileId();

		// create new post
		let newPost = new Post(null, null, this.createPostForm.value.postContent, this.createPostForm.value.postEndDateTime, null, this.createPostForm.value.postStartDateTime, this.createPostForm.value.postTitle);

		this.postService.createPost(newPost)
			.subscribe(status => {
				this.status = status;
				if(status.status === 200) {
					this.listPosts();
					this.createPostForm.reset();
				}else{
					console.log('no valid login');
				}
			});
	}

	currentlySignedIn() : void {

			const decodedJwt = this.jwtHelper.decodeToken(localStorage.getItem('jwt-token'));
			this.organizationService.getOrganization(decodedJwt.auth.organizationId)
				.subscribe(organization => this.organization = organization)
		}
}
