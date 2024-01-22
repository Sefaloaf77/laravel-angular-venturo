import { Injectable } from "@angular/core";
import {
    Router,
    CanActivate,
    ActivatedRouteSnapshot,
    RouterStateSnapshot,
} from "@angular/router";

// import { AuthenticationService } from '../services/auth.service';
import { AuthService } from "src/app/feature/auth/services/auth.service";

@Injectable({ providedIn: "root" })
export class AuthGuard implements CanActivate {
    constructor(
        private router: Router,
        // private authenticationService: AuthenticationService
        private authenticationService: AuthService
    ) {}

    canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot) {
        let userLogin;
        this.authenticationService
            .getProfile()
            .subscribe((user: any) => (userLogin = user));
        // const currentUser = this.authenticationService.currentUser();
        if (userLogin.id) {
            // logged in so return true
            return true;
        }
        if (localStorage.getItem("user")) {
            return true;
        }

        // not logged in so redirect to login page with the return url
        this.router.navigate(["/auth/login"], {
            queryParams: { returnUrl: state.url },
        });
        return false;
    }
}
