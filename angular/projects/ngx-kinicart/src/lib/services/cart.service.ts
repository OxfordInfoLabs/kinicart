import {Injectable} from '@angular/core';
import {KinicartModuleConfig} from '../ngx-kinicart.module';
import {HttpClient} from '@angular/common/http';

@Injectable({
    providedIn: 'root'
})
export class CartService {

    constructor(private config: KinicartModuleConfig,
                private http: HttpClient) {

    }

    public addTopUpCartItem(amount) {
        return this.http.post(this.config.accessHttpURL + '/topup/cartitem',
            amount).toPromise();
    }
}
