import {Component, EventEmitter, OnInit, Output} from '@angular/core';
import {BillingService} from '../../services/billing.service';
import * as _ from 'lodash';

@Component({
    selector: 'kc-billing-address',
    templateUrl: './billing-address.component.html',
    styleUrls: ['./billing-address.component.css']
})
export class BillingAddressComponent implements OnInit {

    @Output() saved = new EventEmitter();

    public editAddress = false;
    public address: any = {};
    public addressString: string;

    constructor(private billingService: BillingService) {
    }

    async ngOnInit() {
        this.address = await this.billingService.getBillingContact() || {};
        this.addressString = _.chain(this.address).values().filter().join(', ').valueOf();
    }

    public async saveAddress() {
        await this.billingService.saveBillingContact(this.address);
        this.addressString = _.chain(this.address).values().filter().join(', ').valueOf();
        this.editAddress = false;
        this.saved.emit(this.address);
    }

}
