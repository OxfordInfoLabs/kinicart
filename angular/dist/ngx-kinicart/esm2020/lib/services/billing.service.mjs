import { Injectable } from '@angular/core';
import * as i0 from "@angular/core";
import * as i1 from "@angular/common/http";
export class BillingService {
    constructor(http) {
        this.http = http;
    }
    getBillingContact() {
        return this.http.get('/account/billingContact').toPromise();
    }
    saveBillingContact(contact) {
        return this.http.post('/account/billingContact', contact).toPromise();
    }
}
BillingService.ɵfac = i0.ɵɵngDeclareFactory({ minVersion: "12.0.0", version: "14.2.12", ngImport: i0, type: BillingService, deps: [{ token: i1.HttpClient }], target: i0.ɵɵFactoryTarget.Injectable });
BillingService.ɵprov = i0.ɵɵngDeclareInjectable({ minVersion: "12.0.0", version: "14.2.12", ngImport: i0, type: BillingService, providedIn: 'root' });
i0.ɵɵngDeclareClassMetadata({ minVersion: "12.0.0", version: "14.2.12", ngImport: i0, type: BillingService, decorators: [{
            type: Injectable,
            args: [{
                    providedIn: 'root'
                }]
        }], ctorParameters: function () { return [{ type: i1.HttpClient }]; } });
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiYmlsbGluZy5zZXJ2aWNlLmpzIiwic291cmNlUm9vdCI6IiIsInNvdXJjZXMiOlsiLi4vLi4vLi4vLi4vLi4vcHJvamVjdHMvbmd4LWtpbmljYXJ0L3NyYy9saWIvc2VydmljZXMvYmlsbGluZy5zZXJ2aWNlLnRzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUFBLE9BQU8sRUFBQyxVQUFVLEVBQUMsTUFBTSxlQUFlLENBQUM7OztBQU16QyxNQUFNLE9BQU8sY0FBYztJQUV2QixZQUFvQixJQUFnQjtRQUFoQixTQUFJLEdBQUosSUFBSSxDQUFZO0lBRXBDLENBQUM7SUFFTSxpQkFBaUI7UUFDcEIsT0FBTyxJQUFJLENBQUMsSUFBSSxDQUFDLEdBQUcsQ0FBQyx5QkFBeUIsQ0FBQyxDQUFDLFNBQVMsRUFBRSxDQUFDO0lBQ2hFLENBQUM7SUFFTSxrQkFBa0IsQ0FBQyxPQUFPO1FBQzdCLE9BQU8sSUFBSSxDQUFDLElBQUksQ0FBQyxJQUFJLENBQUMseUJBQXlCLEVBQUUsT0FBTyxDQUFDLENBQUMsU0FBUyxFQUFFLENBQUM7SUFDMUUsQ0FBQzs7NEdBWlEsY0FBYztnSEFBZCxjQUFjLGNBRlgsTUFBTTs0RkFFVCxjQUFjO2tCQUgxQixVQUFVO21CQUFDO29CQUNSLFVBQVUsRUFBRSxNQUFNO2lCQUNyQiIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7SW5qZWN0YWJsZX0gZnJvbSAnQGFuZ3VsYXIvY29yZSc7XG5pbXBvcnQge0h0dHBDbGllbnR9IGZyb20gJ0Bhbmd1bGFyL2NvbW1vbi9odHRwJztcblxuQEluamVjdGFibGUoe1xuICAgIHByb3ZpZGVkSW46ICdyb290J1xufSlcbmV4cG9ydCBjbGFzcyBCaWxsaW5nU2VydmljZSB7XG5cbiAgICBjb25zdHJ1Y3Rvcihwcml2YXRlIGh0dHA6IEh0dHBDbGllbnQpIHtcblxuICAgIH1cblxuICAgIHB1YmxpYyBnZXRCaWxsaW5nQ29udGFjdCgpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuaHR0cC5nZXQoJy9hY2NvdW50L2JpbGxpbmdDb250YWN0JykudG9Qcm9taXNlKCk7XG4gICAgfVxuXG4gICAgcHVibGljIHNhdmVCaWxsaW5nQ29udGFjdChjb250YWN0KSB7XG4gICAgICAgIHJldHVybiB0aGlzLmh0dHAucG9zdCgnL2FjY291bnQvYmlsbGluZ0NvbnRhY3QnLCBjb250YWN0KS50b1Byb21pc2UoKTtcbiAgICB9XG59XG4iXX0=