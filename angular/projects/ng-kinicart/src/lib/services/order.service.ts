import { Injectable } from '@angular/core';
import { KinibindRequestService } from 'ng-kinibind';
import { KinicartModuleConfig } from '../../ng-kinicart.module';
import * as _ from 'lodash';

@Injectable({
    providedIn: 'root'
})
export class OrderService {

    constructor(private kbRequest: KinibindRequestService,
                private config: KinicartModuleConfig) {

    }

    public getOrders(searchTerm?, startDate?, endDate?) {
        return this.kbRequest.makePostRequest(this.config.accessHttpURL + '/order/orders',
            _.pickBy({searchTerm, startDate, endDate}, _.identity));
    }
}
