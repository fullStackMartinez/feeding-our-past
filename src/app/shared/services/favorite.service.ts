import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {Status} from "../classes/status";
import {Favorite} from "../classes/favorite";
import {Observable} from "rxjs/Observable";

@Injectable ()
export class FavoriteService {

	constructor(protected http : HttpClient ) {

	}

	//define the API endpoint
	private favoriteUrl = "/api/favorite/";


	//call the favorite API and create a new favorite
	createFavorite(favorite : Favorite) : Observable<Status> {
		return (this.http.post<Status>(this.favoriteUrl, favorite));
	}

	//grabs a favorite based on its composite key
	getFavoriteByCompositeKey(favoriteVolunteerId : number, favoritePostId : number) : Observable <Favorite> {
		return (this.http.get<Favorite>(this.favoriteUrl+ "?favoriteVolunteerId=" + favoriteVolunteerId +"&favoritePostId=" + favoritePostId))
	}

	getFavoriteByPostId (favoritePostId : number) : Observable<Favorite[]> {
		return(this.http.get<Favorite[]>(this.favoriteUrl + favoritePostId))
	}


}