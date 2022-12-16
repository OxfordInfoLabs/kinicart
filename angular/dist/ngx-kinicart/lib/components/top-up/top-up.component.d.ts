import { OnInit } from '@angular/core';
import { PaymentService } from '../../services/payment.service';
import { ActivatedRoute } from '@angular/router';
import * as i0 from "@angular/core";
export declare class TopUpComponent implements OnInit {
    private paymentService;
    private route;
    topUpAmount: number;
    status: string;
    constructor(paymentService: PaymentService, route: ActivatedRoute);
    ngOnInit(): void;
    topUp(): Promise<void>;
    viewOrder(): void;
    static ɵfac: i0.ɵɵFactoryDeclaration<TopUpComponent, never>;
    static ɵcmp: i0.ɵɵComponentDeclaration<TopUpComponent, "kc-top-up", never, {}, {}, never, never, false>;
}
