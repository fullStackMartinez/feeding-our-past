import {RouterModule, Routes} from "@angular/router";
import {AuthGuardService, AuthGuardService as AuthGuard} from "./shared/guards/auth.guard";
import {AuthService} from "./shared/services/auth.service";
import {FavoriteService} from "./shared/services/favorite.service";
import {HomeComponent} from "./home/home.component";
import {OrganizationService} from "./shared/services/organization.service";
import {OrganizationSignInService} from "./shared/services/organization.sign.in.service";
import {OrganizationSignUpService} from "./shared/services/organization.sign.up.service";
import {PostService} from "./shared/services/post.service";
import {SessionService} from "./shared/services/session.service";
import {VolunteerService} from "./shared/services/volunteer.service";
import {VolunteerSignInService} from "./shared/services/volunteer.sign.in.service";
import {VolunteerSignUpService} from "./shared/services/volunteer.sign.up.service";


export const allAppComponents = [HomeComponent];

export const routes: Routes = [
	{path: "", component: HomeComponent}
];

export const appRoutingProviders: any[] = [
	AuthService,
	AuthGuardService,
	FavoriteService,
	OrganizationService,
	OrganizationSignInService,
	OrganizationSignUpService,
	PostService,
	VolunteerService,
	VolunteerSignInService,
	VolunteerSignUpService,
	SessionService
];

export const routing = RouterModule.forRoot(routes);