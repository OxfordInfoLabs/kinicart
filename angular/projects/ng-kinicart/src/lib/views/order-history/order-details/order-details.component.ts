import { Component, Input, OnInit } from '@angular/core';

@Component({
    selector: 'kc-order-details',
    templateUrl: './order-details.component.html',
    styleUrls: ['./order-details.component.sass']
})
export class OrderDetailsComponent implements OnInit {

    @Input() order: any;
    public pdfURL: string;
    public Object = Object;

    constructor() {
    }

    ngOnInit() {
    }

}
