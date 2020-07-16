import {
    AfterViewInit,
    Component,
    EventEmitter,
    Input,
    OnDestroy,
    OnInit,
    Output,
    ViewEncapsulation
} from '@angular/core';
import { PaymentService } from '../../../services/payment.service';
import { Subscription } from 'rxjs';

@Component({
    selector: 'kc-payment-method',
    templateUrl: './payment-method.component.html',
    styleUrls: ['./payment-method.component.sass'],
    encapsulation: ViewEncapsulation.None
})
export class PaymentMethodComponent implements OnInit, OnDestroy, AfterViewInit {

    @Input() environment: any;
    @Output('saved') saved: EventEmitter<any> = new EventEmitter();

    public elements: any;
    public cardElement: any;
    public cardholderName: string;
    public errorMessage: string;
    public default = true;

    private stripe: any;
    private stripeSub: Subscription;
    private setupClientSecret: string;

    constructor(private paymentService: PaymentService) {
    }

    ngOnInit() {
        this.paymentService.createStripeSetupIntent().then(setupIntent => {
            this.setupClientSecret = setupIntent.client_secret;
        });
    }

    ngAfterViewInit(): void {
        this.stripeSub = this.paymentService.stripe.subscribe(stripe => {
            if (stripe) {
                this.stripe = stripe;
                this.setupStripeElements();
            }
        });
    }

    ngOnDestroy(): void {
        this.stripeSub.unsubscribe();
    }

    public handleCardSetup() {
        this.errorMessage = null;
        this.paymentService.handleCardSetup(
            this.setupClientSecret,
            this.cardElement,
            this.cardholderName
        ).then(res => {
            if (res.setupIntent) {
                this.paymentService.addPaymentMethod(res.setupIntent.payment_method, this.default)
                    .then(method => {
                        this.saved.emit(res.setupIntent.created);
                    });
            } else if (res.error && res.error.message) {
                this.errorMessage = res.error.message;
            }
        });
    }

    private setupStripeElements() {
        this.elements = this.stripe.elements();
        this.cardElement = this.elements.create('card');
        this.cardElement.mount('#card-element');
    }

}
