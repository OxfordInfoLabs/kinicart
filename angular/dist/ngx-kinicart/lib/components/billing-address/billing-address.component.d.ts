import { OnInit } from '@angular/core';
import { BillingService } from '../../services/billing.service';
import * as i0 from "@angular/core";
export declare class BillingAddressComponent implements OnInit {
    private billingService;
    editAddress: boolean;
    address: any;
    addressString: string;
    constructor(billingService: BillingService);
    ngOnInit(): Promise<void>;
    saveAddress(): Promise<void>;
    static ɵfac: i0.ɵɵFactoryDeclaration<BillingAddressComponent, never>;
    static ɵcmp: i0.ɵɵComponentDeclaration<BillingAddressComponent, "kc-billing-address", never, {}, {}, never, never, false>;
}
