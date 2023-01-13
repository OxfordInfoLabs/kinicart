import { EventEmitter, OnInit } from '@angular/core';
import { PaymentService } from '../../services/payment.service';
import { ActivatedRoute } from '@angular/router';
import { CartService } from '../../services/cart.service';
import { BillingService } from '../../services/billing.service';
import * as i0 from "@angular/core";
export declare class TopUpComponent implements OnInit {
    private paymentService;
    private route;
    private cartService;
    private billingService;
    topUpMessage: string;
    minAmount: number;
    currency: string;
    callbackRoute: string;
    amount: EventEmitter<any>;
    topUpAmount: number;
    status: string;
    billingContact: any;
    loading: boolean;
    constructor(paymentService: PaymentService, route: ActivatedRoute, cartService: CartService, billingService: BillingService);
    ngOnInit(): Promise<void>;
    topUp(): Promise<void>;
    viewOrder(): void;
    billingSaved(contact: any): void;
    static ɵfac: i0.ɵɵFactoryDeclaration<TopUpComponent, never>;
    static ɵcmp: i0.ɵɵComponentDeclaration<TopUpComponent, "kc-top-up", never, { "topUpMessage": "topUpMessage"; "minAmount": "minAmount"; "currency": "currency"; "callbackRoute": "callbackRoute"; }, { "amount": "amount"; }, never, never, false>;
}
