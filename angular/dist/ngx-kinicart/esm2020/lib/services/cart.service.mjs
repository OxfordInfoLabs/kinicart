import { Injectable } from '@angular/core';
import * as i0 from "@angular/core";
import * as i1 from "../ngx-kinicart.module";
import * as i2 from "@angular/common/http";
export class CartService {
    constructor(config, http) {
        this.config = config;
        this.http = http;
    }
    addTopUpCartItem(amount) {
        return this.http.post(this.config.accessHttpURL + '/topup/cartitem', amount).toPromise();
    }
}
CartService.ɵfac = i0.ɵɵngDeclareFactory({ minVersion: "12.0.0", version: "14.3.0", ngImport: i0, type: CartService, deps: [{ token: i1.KinicartModuleConfig }, { token: i2.HttpClient }], target: i0.ɵɵFactoryTarget.Injectable });
CartService.ɵprov = i0.ɵɵngDeclareInjectable({ minVersion: "12.0.0", version: "14.3.0", ngImport: i0, type: CartService, providedIn: 'root' });
i0.ɵɵngDeclareClassMetadata({ minVersion: "12.0.0", version: "14.3.0", ngImport: i0, type: CartService, decorators: [{
            type: Injectable,
            args: [{
                    providedIn: 'root'
                }]
        }], ctorParameters: function () { return [{ type: i1.KinicartModuleConfig }, { type: i2.HttpClient }]; } });
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiY2FydC5zZXJ2aWNlLmpzIiwic291cmNlUm9vdCI6IiIsInNvdXJjZXMiOlsiLi4vLi4vLi4vLi4vLi4vcHJvamVjdHMvbmd4LWtpbmljYXJ0L3NyYy9saWIvc2VydmljZXMvY2FydC5zZXJ2aWNlLnRzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUFBLE9BQU8sRUFBQyxVQUFVLEVBQUMsTUFBTSxlQUFlLENBQUM7Ozs7QUFPekMsTUFBTSxPQUFPLFdBQVc7SUFFcEIsWUFBb0IsTUFBNEIsRUFDNUIsSUFBZ0I7UUFEaEIsV0FBTSxHQUFOLE1BQU0sQ0FBc0I7UUFDNUIsU0FBSSxHQUFKLElBQUksQ0FBWTtJQUVwQyxDQUFDO0lBRU0sZ0JBQWdCLENBQUMsTUFBTTtRQUMxQixPQUFPLElBQUksQ0FBQyxJQUFJLENBQUMsSUFBSSxDQUFDLElBQUksQ0FBQyxNQUFNLENBQUMsYUFBYSxHQUFHLGlCQUFpQixFQUMvRCxNQUFNLENBQUMsQ0FBQyxTQUFTLEVBQUUsQ0FBQztJQUM1QixDQUFDOzt3R0FWUSxXQUFXOzRHQUFYLFdBQVcsY0FGUixNQUFNOzJGQUVULFdBQVc7a0JBSHZCLFVBQVU7bUJBQUM7b0JBQ1IsVUFBVSxFQUFFLE1BQU07aUJBQ3JCIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHtJbmplY3RhYmxlfSBmcm9tICdAYW5ndWxhci9jb3JlJztcbmltcG9ydCB7S2luaWNhcnRNb2R1bGVDb25maWd9IGZyb20gJy4uL25neC1raW5pY2FydC5tb2R1bGUnO1xuaW1wb3J0IHtIdHRwQ2xpZW50fSBmcm9tICdAYW5ndWxhci9jb21tb24vaHR0cCc7XG5cbkBJbmplY3RhYmxlKHtcbiAgICBwcm92aWRlZEluOiAncm9vdCdcbn0pXG5leHBvcnQgY2xhc3MgQ2FydFNlcnZpY2Uge1xuXG4gICAgY29uc3RydWN0b3IocHJpdmF0ZSBjb25maWc6IEtpbmljYXJ0TW9kdWxlQ29uZmlnLFxuICAgICAgICAgICAgICAgIHByaXZhdGUgaHR0cDogSHR0cENsaWVudCkge1xuXG4gICAgfVxuXG4gICAgcHVibGljIGFkZFRvcFVwQ2FydEl0ZW0oYW1vdW50KSB7XG4gICAgICAgIHJldHVybiB0aGlzLmh0dHAucG9zdCh0aGlzLmNvbmZpZy5hY2Nlc3NIdHRwVVJMICsgJy90b3B1cC9jYXJ0aXRlbScsXG4gICAgICAgICAgICBhbW91bnQpLnRvUHJvbWlzZSgpO1xuICAgIH1cbn1cbiJdfQ==