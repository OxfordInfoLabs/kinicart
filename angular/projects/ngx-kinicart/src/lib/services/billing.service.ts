import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';

@Injectable({
    providedIn: 'root'
})
export class BillingService {

    constructor(private http: HttpClient) {

    }

    public getBillingContact() {
        return this.http.get('/account/billingContact').toPromise();
    }

    public saveBillingContact(contact) {
        return this.http.post('/account/billingContact', contact).toPromise();
    }
}
