import {Injectable} from "@angular/core";
import {Status} from "../classes/status";
import {Post} from "../classes/post";
import {PostAuthor} from "../classes/post.author"
import {Observable} from "rxjs/Observable";
import {HttpClient} from "@angular/common/http";

@Injectable ()
export class PostService {

	constructor(protected http : HttpClient ) {}

	//define the API endpoint
	private postUrl = "api/post/";

	// call to the post API and delete the post in question
	deletePost(postId: number) : Observable<Status> {
		return(this.http.delete<Status>(this.postUrl + postId));

	}

	// call to the post API and edit the post in question
	editPost(post : Post) : Observable<Status> {
		return(this.http.put<Status>(this.postUrl + post.id, post));
	}

	// call to the post API and create the post in question
	createPost(post : Post) : Observable<Status> {
		return(this.http.post<Status>(this.postUrl, post));
	}

	// call to the post API and get a post object based on its Id
	getPost(postId : number) : Observable<Post> {
		return(this.http.get<Post>(this.postUrl + postId));

	}

	// call to the API and get an array of posts based off the postOrganizationId
	getPostByPostOrganizationId(postOrganizationId : number) : Observable<PostAuthor[]> {
		return(this.http.get<PostAuthor[]>(this.postUrl + postOrganizationId));

	}

	// call to post API and get an array of posts based off the postEndDateTime
	getPostByPostEndDateTime() : Observable<PostAuthor[]> {
		return(this.http.get<PostAuthor[]>(this.postUrl));

	}

}