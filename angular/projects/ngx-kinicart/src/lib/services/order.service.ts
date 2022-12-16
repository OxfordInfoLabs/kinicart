import { Injectable } from '@angular/core';
import { KinicartModuleConfig } from '../ngx-kinicart.module';
import * as _ from 'lodash';
import {HttpClient} from '@angular/common/http';

@Injectable({
    providedIn: 'root'
})
export class OrderService {

    constructor(private config: KinicartModuleConfig,
                private http: HttpClient) {

    }

    public getOrders(searchTerm?, startDate?, endDate?) {
        return this.http.post(this.config.accessHttpURL + '/order/orders',
            _.pickBy({searchTerm, startDate, endDate}, _.identity));
    }
}
