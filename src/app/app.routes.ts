import {RouterModule, Routes} from "@angular/router";
import {HomeComponent} from "./home/home.component";
import {OrganizationService} from "./shared/services/organization.service";


export const allAppComponents = [HomeComponent];

export const routes: Routes = [
	{path: "", component: HomeComponent}
];

export const appRoutingProviders: any[] = [
	OrganizationService
];

export const routing = RouterModule.forRoot(routes);