import { Injectable } from '@angular/core';
import * as i0 from "@angular/core";
import * as i1 from "../ngx-kinicart.module";
import * as i2 from "@angular/common/http";
export class PaymentService {
    constructor(config, http) {
        this.config = config;
        this.http = http;
    }
    getStripeCheckoutSessionURL(lineItems = [], mode = 'payment', cancelURL = '/cancel', successURL = '/success', currency = 'gbp') {
        return this.http.post(this.config.accessHttpURL + '/stripe/checkoutSession', {
            lineItems, mode, cancelURL, successURL, currency
        }).toPromise().then((sessionURL) => {
            return sessionURL;
        });
    }
    removePaymentMethod(methodId, type) {
        return this.http.get(this.config.accessHttpURL + '/payment/remove', {
            params: { methodId, provider: type }
        }).toPromise();
    }
}
PaymentService.ɵfac = i0.ɵɵngDeclareFactory({ minVersion: "12.0.0", version: "14.2.12", ngImport: i0, type: PaymentService, deps: [{ token: i1.KinicartModuleConfig }, { token: i2.HttpClient }], target: i0.ɵɵFactoryTarget.Injectable });
PaymentService.ɵprov = i0.ɵɵngDeclareInjectable({ minVersion: "12.0.0", version: "14.2.12", ngImport: i0, type: PaymentService, providedIn: 'root' });
i0.ɵɵngDeclareClassMetadata({ minVersion: "12.0.0", version: "14.2.12", ngImport: i0, type: PaymentService, decorators: [{
            type: Injectable,
            args: [{
                    providedIn: 'root'
                }]
        }], ctorParameters: function () { return [{ type: i1.KinicartModuleConfig }, { type: i2.HttpClient }]; } });
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoicGF5bWVudC5zZXJ2aWNlLmpzIiwic291cmNlUm9vdCI6IiIsInNvdXJjZXMiOlsiLi4vLi4vLi4vLi4vLi4vcHJvamVjdHMvbmd4LWtpbmljYXJ0L3NyYy9saWIvc2VydmljZXMvcGF5bWVudC5zZXJ2aWNlLnRzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUFBLE9BQU8sRUFBRSxVQUFVLEVBQUUsTUFBTSxlQUFlLENBQUM7Ozs7QUFTM0MsTUFBTSxPQUFPLGNBQWM7SUFFdkIsWUFBb0IsTUFBNEIsRUFDNUIsSUFBZ0I7UUFEaEIsV0FBTSxHQUFOLE1BQU0sQ0FBc0I7UUFDNUIsU0FBSSxHQUFKLElBQUksQ0FBWTtJQUNwQyxDQUFDO0lBRU0sMkJBQTJCLENBQUMsU0FBUyxHQUFHLEVBQUUsRUFBRSxJQUFJLEdBQUcsU0FBUyxFQUFFLFNBQVMsR0FBRyxTQUFTLEVBQUUsVUFBVSxHQUFHLFVBQVUsRUFBRSxRQUFRLEdBQUcsS0FBSztRQUNqSSxPQUFPLElBQUksQ0FBQyxJQUFJLENBQUMsSUFBSSxDQUFDLElBQUksQ0FBQyxNQUFNLENBQUMsYUFBYSxHQUFHLHlCQUF5QixFQUFFO1lBQ3pFLFNBQVMsRUFBRSxJQUFJLEVBQUUsU0FBUyxFQUFFLFVBQVUsRUFBRSxRQUFRO1NBQ25ELENBQUMsQ0FBQyxTQUFTLEVBQUUsQ0FBQyxJQUFJLENBQUMsQ0FBQyxVQUFrQixFQUFFLEVBQUU7WUFDdkMsT0FBTyxVQUFVLENBQUM7UUFDdEIsQ0FBQyxDQUFDLENBQUM7SUFDUCxDQUFDO0lBRU0sbUJBQW1CLENBQUMsUUFBUSxFQUFFLElBQUk7UUFDckMsT0FBTyxJQUFJLENBQUMsSUFBSSxDQUFDLEdBQUcsQ0FBQyxJQUFJLENBQUMsTUFBTSxDQUFDLGFBQWEsR0FBRyxpQkFBaUIsRUFBRTtZQUNoRSxNQUFNLEVBQUUsRUFBRSxRQUFRLEVBQUUsUUFBUSxFQUFFLElBQUksRUFBRTtTQUN2QyxDQUFDLENBQUMsU0FBUyxFQUFFLENBQUM7SUFDbkIsQ0FBQzs7NEdBbEJRLGNBQWM7Z0hBQWQsY0FBYyxjQUZYLE1BQU07NEZBRVQsY0FBYztrQkFIMUIsVUFBVTttQkFBQztvQkFDUixVQUFVLEVBQUUsTUFBTTtpQkFDckIiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgeyBJbmplY3RhYmxlIH0gZnJvbSAnQGFuZ3VsYXIvY29yZSc7XG5pbXBvcnQgeyBLaW5pY2FydE1vZHVsZUNvbmZpZyB9IGZyb20gJy4uL25neC1raW5pY2FydC5tb2R1bGUnO1xuaW1wb3J0IHtIdHRwQ2xpZW50fSBmcm9tICdAYW5ndWxhci9jb21tb24vaHR0cCc7XG5cbmRlY2xhcmUgdmFyIHdpbmRvdzogYW55O1xuXG5ASW5qZWN0YWJsZSh7XG4gICAgcHJvdmlkZWRJbjogJ3Jvb3QnXG59KVxuZXhwb3J0IGNsYXNzIFBheW1lbnRTZXJ2aWNlIHtcblxuICAgIGNvbnN0cnVjdG9yKHByaXZhdGUgY29uZmlnOiBLaW5pY2FydE1vZHVsZUNvbmZpZyxcbiAgICAgICAgICAgICAgICBwcml2YXRlIGh0dHA6IEh0dHBDbGllbnQpIHtcbiAgICB9XG5cbiAgICBwdWJsaWMgZ2V0U3RyaXBlQ2hlY2tvdXRTZXNzaW9uVVJMKGxpbmVJdGVtcyA9IFtdLCBtb2RlID0gJ3BheW1lbnQnLCBjYW5jZWxVUkwgPSAnL2NhbmNlbCcsIHN1Y2Nlc3NVUkwgPSAnL3N1Y2Nlc3MnLCBjdXJyZW5jeSA9ICdnYnAnKTogUHJvbWlzZTxzdHJpbmc+IHtcbiAgICAgICAgcmV0dXJuIHRoaXMuaHR0cC5wb3N0KHRoaXMuY29uZmlnLmFjY2Vzc0h0dHBVUkwgKyAnL3N0cmlwZS9jaGVja291dFNlc3Npb24nLCB7XG4gICAgICAgICAgICBsaW5lSXRlbXMsIG1vZGUsIGNhbmNlbFVSTCwgc3VjY2Vzc1VSTCwgY3VycmVuY3lcbiAgICAgICAgfSkudG9Qcm9taXNlKCkudGhlbigoc2Vzc2lvblVSTDogc3RyaW5nKSA9PiB7XG4gICAgICAgICAgICByZXR1cm4gc2Vzc2lvblVSTDtcbiAgICAgICAgfSk7XG4gICAgfVxuXG4gICAgcHVibGljIHJlbW92ZVBheW1lbnRNZXRob2QobWV0aG9kSWQsIHR5cGUpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuaHR0cC5nZXQodGhpcy5jb25maWcuYWNjZXNzSHR0cFVSTCArICcvcGF5bWVudC9yZW1vdmUnLCB7XG4gICAgICAgICAgICBwYXJhbXM6IHsgbWV0aG9kSWQsIHByb3ZpZGVyOiB0eXBlIH1cbiAgICAgICAgfSkudG9Qcm9taXNlKCk7XG4gICAgfVxuXG59XG4iXX0=