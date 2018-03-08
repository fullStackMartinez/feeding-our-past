///<reference path="../../node_modules/@angular/common/http/src/interceptor.d.ts"/>
import {RouterModule, Routes} from "@angular/router";
import {AuthGuardService, AuthGuardService as AuthGuard} from "./shared/guards/auth.guard";
import {AuthService} from "./shared/services/auth.service";
import {FavoriteService} from "./shared/services/favorite.service";
import {HomeComponent} from "./home/home.component";
import {OrganizationComponent} from "./organization/organization.component";
import {OrganizationService} from "./shared/services/organization.service";
import {OrganizationSignInComponent} from "./organization-sign-in/organization.sign.in.component"
import {OrganizationSignInService} from "./shared/services/organization.sign.in.service";
import {OrganizationSignUpComponent} from "./organization-sign-up/organization.sign.up.component";
import {OrganizationSignUpService} from "./shared/services/organization.sign.up.service";
import {PostService} from "./shared/services/post.service";
import {SeniorComponent} from "./senior/senior.component";
import {SessionService} from "./shared/services/session.service";
import {VolunteerComponent} from "./volunteer/volunteer.component";
import {VolunteerService} from "./shared/services/volunteer.service";
import {VolunteerSignInService} from "./shared/services/volunteer.sign.in.service";
import {VolunteerSignUpService} from "./shared/services/volunteer.sign.up.service";
import {HTTP_INTERCEPTORS} from "@angular/common/http";
import {DeepDiveInterceptor} from "./shared/interceptors/deep.dive.interceptor";
import {NavbarComponent} from "./shared/components/main-nav.component";



export const allAppComponents = [HomeComponent,NavbarComponent,OrganizationComponent, OrganizationSignInComponent, OrganizationSignUpComponent, SeniorComponent,VolunteerComponent];

export const routes: Routes = [
	//{path: "here", component : yourComponent}
	{path: "", component: HomeComponent},
	{path: "organization", component: OrganizationComponent},
	{path: "organization-sign-up", component: OrganizationSignUpComponent},
	{path: "organization-sign-in", component: OrganizationSignInComponent},
	{path: "senior", component: SeniorComponent},
	{path: "volunteer", component: VolunteerComponent}
];

const services: any[] = [
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

const providers : any[] = [
	{provide: HTTP_INTERCEPTORS, useClass: DeepDiveInterceptor, multi: true}
];

export const appRoutingProviders: any[] = [providers, services];

export const routing = RouterModule.forRoot(routes);