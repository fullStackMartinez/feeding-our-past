import {Component, OnInit} from "@angular/core";
import {Post} from "../shared/classes/post";
import {Status} from "../shared/classes/status";
import {PostService} from "../shared/services/post.service";

@Component({
	template: require("./home.component.html")
})

export class HomeComponent implements OnInit {
	posts: Post[] = [];
	status: Status = null;

	constructor(
		private postService: PostService
	){}

	ngOnInit() : void {
		this.listPosts();
	}

	listPosts(): void {
		this.postService.getPostByPostEndDateTime()
			.subscribe(posts =>  {this.posts = posts
			console.log(this.posts)
			});
	}
}

