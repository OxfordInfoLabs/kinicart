import { HttpClient } from '@angular/common/http';
import * as i0 from "@angular/core";
export declare class BillingService {
    private http;
    constructor(http: HttpClient);
    getBillingContact(): Promise<Object>;
    saveBillingContact(contact: any): Promise<Object>;
    static ɵfac: i0.ɵɵFactoryDeclaration<BillingService, never>;
    static ɵprov: i0.ɵɵInjectableDeclaration<BillingService>;
}
