import { KinicartModuleConfig } from '../ngx-kinicart.module';
import { HttpClient } from '@angular/common/http';
import * as i0 from "@angular/core";
export declare class PaymentService {
    private config;
    private http;
    constructor(config: KinicartModuleConfig, http: HttpClient);
    getStripeCheckoutSessionURL(lineItems?: any[], mode?: string, cancelURL?: string, successURL?: string, currency?: string): Promise<string>;
    removePaymentMethod(methodId: any, type: any): Promise<Object>;
    static ɵfac: i0.ɵɵFactoryDeclaration<PaymentService, never>;
    static ɵprov: i0.ɵɵInjectableDeclaration<PaymentService>;
}
