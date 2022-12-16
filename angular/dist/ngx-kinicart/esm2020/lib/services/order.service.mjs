import { Injectable } from '@angular/core';
import * as _ from 'lodash';
import * as i0 from "@angular/core";
import * as i1 from "../ngx-kinicart.module";
import * as i2 from "@angular/common/http";
export class OrderService {
    constructor(config, http) {
        this.config = config;
        this.http = http;
    }
    getOrders(searchTerm, startDate, endDate) {
        return this.http.post(this.config.accessHttpURL + '/order/orders', _.pickBy({ searchTerm, startDate, endDate }, _.identity));
    }
}
OrderService.ɵfac = i0.ɵɵngDeclareFactory({ minVersion: "12.0.0", version: "14.2.12", ngImport: i0, type: OrderService, deps: [{ token: i1.KinicartModuleConfig }, { token: i2.HttpClient }], target: i0.ɵɵFactoryTarget.Injectable });
OrderService.ɵprov = i0.ɵɵngDeclareInjectable({ minVersion: "12.0.0", version: "14.2.12", ngImport: i0, type: OrderService, providedIn: 'root' });
i0.ɵɵngDeclareClassMetadata({ minVersion: "12.0.0", version: "14.2.12", ngImport: i0, type: OrderService, decorators: [{
            type: Injectable,
            args: [{
                    providedIn: 'root'
                }]
        }], ctorParameters: function () { return [{ type: i1.KinicartModuleConfig }, { type: i2.HttpClient }]; } });
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoib3JkZXIuc2VydmljZS5qcyIsInNvdXJjZVJvb3QiOiIiLCJzb3VyY2VzIjpbIi4uLy4uLy4uLy4uLy4uL3Byb2plY3RzL25neC1raW5pY2FydC9zcmMvbGliL3NlcnZpY2VzL29yZGVyLnNlcnZpY2UudHMiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IkFBQUEsT0FBTyxFQUFFLFVBQVUsRUFBRSxNQUFNLGVBQWUsQ0FBQztBQUUzQyxPQUFPLEtBQUssQ0FBQyxNQUFNLFFBQVEsQ0FBQzs7OztBQU01QixNQUFNLE9BQU8sWUFBWTtJQUVyQixZQUFvQixNQUE0QixFQUM1QixJQUFnQjtRQURoQixXQUFNLEdBQU4sTUFBTSxDQUFzQjtRQUM1QixTQUFJLEdBQUosSUFBSSxDQUFZO0lBRXBDLENBQUM7SUFFTSxTQUFTLENBQUMsVUFBVyxFQUFFLFNBQVUsRUFBRSxPQUFRO1FBQzlDLE9BQU8sSUFBSSxDQUFDLElBQUksQ0FBQyxJQUFJLENBQUMsSUFBSSxDQUFDLE1BQU0sQ0FBQyxhQUFhLEdBQUcsZUFBZSxFQUM3RCxDQUFDLENBQUMsTUFBTSxDQUFDLEVBQUMsVUFBVSxFQUFFLFNBQVMsRUFBRSxPQUFPLEVBQUMsRUFBRSxDQUFDLENBQUMsUUFBUSxDQUFDLENBQUMsQ0FBQztJQUNoRSxDQUFDOzswR0FWUSxZQUFZOzhHQUFaLFlBQVksY0FGVCxNQUFNOzRGQUVULFlBQVk7a0JBSHhCLFVBQVU7bUJBQUM7b0JBQ1IsVUFBVSxFQUFFLE1BQU07aUJBQ3JCIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHsgSW5qZWN0YWJsZSB9IGZyb20gJ0Bhbmd1bGFyL2NvcmUnO1xuaW1wb3J0IHsgS2luaWNhcnRNb2R1bGVDb25maWcgfSBmcm9tICcuLi9uZ3gta2luaWNhcnQubW9kdWxlJztcbmltcG9ydCAqIGFzIF8gZnJvbSAnbG9kYXNoJztcbmltcG9ydCB7SHR0cENsaWVudH0gZnJvbSAnQGFuZ3VsYXIvY29tbW9uL2h0dHAnO1xuXG5ASW5qZWN0YWJsZSh7XG4gICAgcHJvdmlkZWRJbjogJ3Jvb3QnXG59KVxuZXhwb3J0IGNsYXNzIE9yZGVyU2VydmljZSB7XG5cbiAgICBjb25zdHJ1Y3Rvcihwcml2YXRlIGNvbmZpZzogS2luaWNhcnRNb2R1bGVDb25maWcsXG4gICAgICAgICAgICAgICAgcHJpdmF0ZSBodHRwOiBIdHRwQ2xpZW50KSB7XG5cbiAgICB9XG5cbiAgICBwdWJsaWMgZ2V0T3JkZXJzKHNlYXJjaFRlcm0/LCBzdGFydERhdGU/LCBlbmREYXRlPykge1xuICAgICAgICByZXR1cm4gdGhpcy5odHRwLnBvc3QodGhpcy5jb25maWcuYWNjZXNzSHR0cFVSTCArICcvb3JkZXIvb3JkZXJzJyxcbiAgICAgICAgICAgIF8ucGlja0J5KHtzZWFyY2hUZXJtLCBzdGFydERhdGUsIGVuZERhdGV9LCBfLmlkZW50aXR5KSk7XG4gICAgfVxufVxuIl19