import { Component, EventEmitter, Input, OnInit } from '@angular/core';
import { KinibindModel, KinibindRequestService } from 'ng-kinibind';
import { PaymentService } from '../../services/payment.service';

@Component({
    selector: 'kc-payment-methods',
    templateUrl: './payment-methods.component.html',
    styleUrls: ['./payment-methods.component.sass']
})
export class PaymentMethodsComponent implements OnInit {

    @Input() environment: any;

    public paymentSources: KinibindModel = new KinibindModel([]);
    public reload: EventEmitter<boolean> = new EventEmitter<boolean>();
    public paymentLoading;
    public addPaymentMethod = false;

    constructor(private kbRequest: KinibindRequestService,
                private paymentService: PaymentService) {

    }

    ngOnInit() {
        this.paymentService.getPaymentMethods().then(methods => {
            this.paymentSources.data = methods;
        });
    }

    public removeCard(cardId, index, type) {
        if (window.confirm('Are you sure you would like to delete this payment method?')) {
            this.paymentLoading = index;
            this.paymentService.removePaymentMethod(cardId, type).then(() => {
                this.reload.next(true);
                this.paymentLoading = null;
            }).catch(() => this.paymentLoading = null);
        }
    }

    public makeDefault(cardId, index) {
        this.paymentLoading = index;
        this.paymentService.setDefaultPaymentMethod(cardId)
            .then(() => {
                this.reload.next(true);
                this.paymentLoading = null;
            }).catch(() => this.paymentLoading = null);
    }
}
