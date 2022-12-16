import { Injectable } from '@angular/core';
import { KinicartModuleConfig } from '../ngx-kinicart.module';
import {HttpClient} from '@angular/common/http';

declare var window: any;

@Injectable({
    providedIn: 'root'
})
export class PaymentService {

    constructor(private config: KinicartModuleConfig,
                private http: HttpClient) {
    }

    public getStripeCheckoutSessionURL(lineItems = [], mode = 'payment', cancelURL = '/cancel', successURL = '/success', currency = 'gbp'): Promise<string> {
        return this.http.post(this.config.accessHttpURL + '/stripe/checkoutSession', {
            lineItems, mode, cancelURL, successURL, currency
        }).toPromise().then((sessionURL: string) => {
            return sessionURL;
        });
    }

    public removePaymentMethod(methodId, type) {
        return this.http.get(this.config.accessHttpURL + '/payment/remove', {
            params: { methodId, provider: type }
        }).toPromise();
    }

}
