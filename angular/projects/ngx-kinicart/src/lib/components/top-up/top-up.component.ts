import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {PaymentService} from '../../services/payment.service';
import {ActivatedRoute} from '@angular/router';
import {CartService} from '../../services/cart.service';
import {BillingService} from '../../services/billing.service';

@Component({
    selector: 'kc-top-up',
    templateUrl: './top-up.component.html',
    styleUrls: ['./top-up.component.css']
})
export class TopUpComponent implements OnInit {

    @Input() topUpMessage: string;
    @Input() minAmount: number;
    @Input() currency = '£';
    @Input() callbackRoute = '/account/top-up';

    @Output() amount = new EventEmitter();

    public topUpAmount: number;
    public status: string;
    public billingContact: any = null;
    public loading = true;

    constructor(private paymentService: PaymentService,
                private route: ActivatedRoute,
                private cartService: CartService,
                private billingService: BillingService) {
    }

    async ngOnInit() {
        this.route.params.subscribe(params => {
            this.status = params.status || '';
        });

        this.billingContact = await this.billingService.getBillingContact();
        this.loading = false;
    }

    public async topUp() {
        if (this.topUpAmount >= 5) {
            await this.cartService.addTopUpCartItem(this.topUpAmount);

            const lineItem = {
                price_data: {
                    currency: 'gbp',
                    unit_amount: this.topUpAmount * 100,
                    product_data: {
                        name: 'Account Top Up'
                    }
                },
                quantity: 1
            };
            const checkoutSession: string = await this.paymentService.getStripeCheckoutSessionURL(
                [lineItem],
                'payment',
                window.location.origin + this.callbackRoute + '/cancel',
                window.location.origin + this.callbackRoute + '/success',
            );

            window.location.href = checkoutSession;
        } else {
            window.alert('Please enter a top up amount greater than 5.');
        }
    }

    public viewOrder() {

    }

    public billingSaved(contact) {
        this.billingContact = contact;
    }

}
