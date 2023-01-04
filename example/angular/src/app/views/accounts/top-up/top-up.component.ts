import {Component, OnInit} from '@angular/core';

@Component({
    selector: 'app-top-up',
    templateUrl: './top-up.component.html',
    styleUrls: ['./top-up.component.sass']
})
export class TopUpComponent implements OnInit {

    public topUpMessage = '';

    constructor() {
    }

    ngOnInit(): void {
    }

    public amountChange(amount: any) {
        if (amount) {
            this.topUpMessage = 'You are about to top up ' + amount;
        } else {
            this.topUpMessage = '';
        }
    }

}
