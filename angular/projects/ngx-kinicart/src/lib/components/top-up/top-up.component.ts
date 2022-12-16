import {Component, OnInit} from '@angular/core';
import {PaymentService} from '../../services/payment.service';
import {ActivatedRoute} from '@angular/router';

@Component({
    selector: 'kc-top-up',
    templateUrl: './top-up.component.html',
    styleUrls: ['./top-up.component.css']
})
export class TopUpComponent implements OnInit {

    public topUpAmount: number;
    public status: string;

    constructor(private paymentService: PaymentService,
                private route: ActivatedRoute) {
    }

    ngOnInit(): void {
        this.route.params.subscribe(params => {
            this.status = params.status || '';
        });
    }

    public async topUp() {
        if (this.topUpAmount >= 5) {
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
                window.location.href + '/cancel',
                window.location.href + '/success',
            );

            window.location.href = checkoutSession;
        } else {
            window.alert('Please enter a top up amount greater than 5.');
        }
    }

    public viewOrder() {

    }

}
