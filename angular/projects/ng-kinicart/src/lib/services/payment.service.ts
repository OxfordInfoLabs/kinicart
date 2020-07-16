import { Injectable } from '@angular/core';
import { KinibindRequestService } from 'ng-kinibind';
import { KinicartModuleConfig } from '../../ng-kinicart.module';
import { BehaviorSubject } from 'rxjs';

declare var window: any;

@Injectable({
    providedIn: 'root'
})
export class PaymentService {

    public stripe: BehaviorSubject<any> = new BehaviorSubject<any>(null);

    private publishableKey: string;

    constructor(private kbRequest: KinibindRequestService,
                private config: KinicartModuleConfig) {

        this.kbRequest.makeGetRequest(this.config.accessHttpURL + '/stripe/publishableKey')
            .toPromise().then(key => {
            this.publishableKey = key;
            this.stripe.next(window.Stripe(key));
        });
    }

    public createStripeSetupIntent() {
        return this.kbRequest.makeGetRequest(this.config.accessHttpURL + '/stripe/createSetupIntent')
            .toPromise()
            .then(res => {
                return res;
            })
    }

    public handleCardSetup(clientSecret, cardElement, cardholderName) {
        const stripe = this.stripe.getValue();
        return stripe.handleCardSetup(clientSecret, cardElement, {
            payment_method_data: {
                billing_details: {
                    name: cardholderName
                }
            }
        });
    }

    public addPaymentMethod(paymentMethod, defaultMethod = true) {
        return this.kbRequest.makePostRequest(this.config.accessHttpURL +
            `/payment/saveMethod?defaultMethod=${defaultMethod}&provider=stripe`, {
            paymentMethod
        }).toPromise();
    }

    public getPaymentMethods() {
        return this.kbRequest.makeGetRequest(this.config.accessHttpURL + '/payment/methods')
            .toPromise();
    }

    public setDefaultPaymentMethod(methodId) {
        return this.kbRequest.makeGetRequest(this.config.accessHttpURL + '/payment/default', {
            params: { methodId }
        }).toPromise();
    }

    public removePaymentMethod(methodId, type) {
        return this.kbRequest.makeGetRequest(this.config.accessHttpURL + '/payment/remove', {
            params: { methodId, provider: type }
        }).toPromise();
    }

}
