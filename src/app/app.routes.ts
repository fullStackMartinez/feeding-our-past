///<reference path="../../node_modules/@angular/common/http/src/interceptor.d.ts"/>

// services
import {AuthGuardService, AuthGuardService as AuthGuard} from "./shared/guards/auth.guard";
import {AuthService} from "./shared/services/auth.service";
import {CookieService} from "ng2-cookies";
import {FavoriteService} from "./shared/services/favorite.service";
import {LocationService} from "./shared/services/location.service";
import {OrganizationService} from "./shared/services/organization.service";
import {OrganizationSignInService} from "./shared/services/organization.sign.in.service";
import {OrganizationSignUpService} from "./shared/services/organization.sign.up.service";
import {PostService} from "./shared/services/post.service";
import {SessionService} from "./shared/services/session.service";
import {VolunteerService} from "./shared/services/volunteer.service";
import {VolunteerSignInService} from "./shared/services/volunteer.sign.in.service";
import {VolunteerSignUpService} from "./shared/services/volunteer.sign.up.service";

// components
import {AboutComponent} from "./about/about.component";
import {HomeComponent} from "./home/home.component";
import {NavbarComponent} from "./shared/components/main-nav.component";
import {OrganizationComponent} from "./organization/organization.component";
import {OrganizationSignInComponent} from "./organization-sign-in/organization.sign.in.component"
import {OrganizationSignUpComponent} from "./organization-sign-up/organization.sign.up.component";
import {SeniorComponent} from "./senior/senior.component";
import {VolunteerComponent} from "./volunteer/volunteer.component";
import {VolunteerSignInComponent} from "./volunteer-sign-in/volunteer.sign.in.component";
import {VolunteerSignUpComponent} from "./volunteer-sign-up/volunteer.sign.up.component";


import {HTTP_INTERCEPTORS} from "@angular/common/http";
import {DeepDiveInterceptor} from "./shared/interceptors/deep.dive.interceptor";
import {RouterModule, Routes} from "@angular/router";
import {APP_BASE_HREF} from "@angular/common";
import {FooterComponent} from "./shared/components/footer.component";


export const allAppComponents = [AboutComponent, HomeComponent,NavbarComponent, OrganizationSignInComponent, OrganizationSignUpComponent, SeniorComponent,VolunteerComponent, VolunteerSignInComponent, VolunteerSignUpComponent];

export const routes: Routes = [
	//{path: "here", component : yourComponent}
	{path: "", component: HomeComponent},
	{path: "about", component: AboutComponent},
	{path: "organization", component: OrganizationComponent},
	{path: "organization-sign-up", component: OrganizationSignUpComponent},
	{path: "organization-sign-in", component: OrganizationSignInComponent},
	{path: "senior", component: SeniorComponent},
	{path: "volunteer", component: VolunteerComponent},
	{path: "volunteer-sign-in", component: VolunteerSignInComponent},
	{path: "volunteer-sign-up", component: VolunteerSignUpComponent}
];

const services: any[] = [
	AuthService,
	AuthGuardService,
	CookieService,
	FavoriteService,
	LocationService,
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
	{provide: APP_BASE_HREF, useValue: window["_base_href"]},
	{provide: HTTP_INTERCEPTORS, useClass: DeepDiveInterceptor, multi: true}
];

export const appRoutingProviders: any[] = [providers, services];

export const routing = RouterModule.forRoot(routes);