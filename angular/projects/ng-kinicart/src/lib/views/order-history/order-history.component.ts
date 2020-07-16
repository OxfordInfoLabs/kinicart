import { Component, OnInit } from '@angular/core';
import { DateAdapter, MAT_DATE_FORMATS, MAT_DATE_LOCALE } from '@angular/material/core';
import { MomentDateAdapter } from '@angular/material-moment-adapter';
import { BehaviorSubject } from 'rxjs/internal/BehaviorSubject';
import { merge } from 'rxjs/internal/observable/merge';
import * as moment from 'moment';
import * as _ from 'lodash';
import { debounceTime, distinctUntilChanged, map, switchMap } from 'rxjs/operators';
import { of } from 'rxjs';
import { OrderService } from '../../services/order.service';

export const MY_FORMATS = {
    parse: {
        dateInput: 'DD/MM/YYYY',
    },
    display: {
        dateInput: 'DD/MM/YYYY',
        monthYearLabel: 'DD/MM/YYYY',
        dateA11yLabel: 'DD/MM/YYYY',
        monthYearA11yLabel: 'DD/MM/YYYY',
    },
};

@Component({
    selector: 'kc-order-history',
    templateUrl: './order-history.component.html',
    styleUrls: ['./order-history.component.sass'],
    providers: [
        { provide: DateAdapter, useClass: MomentDateAdapter, deps: [MAT_DATE_LOCALE] },
        { provide: MAT_DATE_FORMATS, useValue: MY_FORMATS },
    ]
})
export class OrderHistoryComponent implements OnInit {

    public downloadURL: string;
    public orders: any[];

    public startDate = new BehaviorSubject<string>('');
    public endDate = new BehaviorSubject<string>('');
    public searchText = new BehaviorSubject<string>('');
    public pageSize = new BehaviorSubject<number>(10);
    public page = new BehaviorSubject<number>(1);

    public ordersSize: number;
    public allSelected = false;
    public selectionMade = false;
    public selectedOrder: any;
    public viewOrderDetails = false;

    public moment = moment;
    public lodash = _;

    constructor(private orderService: OrderService) {
    }

    ngOnInit() {
        merge(this.searchText, this.startDate, this.endDate, this.pageSize, this.page)
            .pipe(
                debounceTime(300),
                distinctUntilChanged(),
                switchMap(() =>
                    this.getOrders()
                )
            )
            .subscribe((orders: any) => {
                this.orders = orders;
            });
    }

    public search(searchTerm: string) {
        this.searchText.next(searchTerm);
    }

    public updatePage(pageEvent) {
        const pageSize = this.pageSize.getValue();

        if (pageEvent.pageSize !== pageSize) {
            this.pageSize.next(pageEvent.pageSize);
        } else {
            this.page.next(pageEvent.pageIndex + 1);
        }
    }

    public toggleSelectAllOrders() {
        this.allSelected = !this.allSelected;
        this.selectionMade = this.allSelected;
        this.orders = this.lodash.map(this.orders, domain => {
            domain.selected = this.allSelected;
            return domain;
        });
    }

    public toggleOrderSelected(order) {
        order.selected = !order.selected;
        this.selectionMade = this.lodash.some(this.orders, 'selected');
    }

    public viewOrder(order) {
        this.selectedOrder = order;
        this.viewOrderDetails = true;
    }

    private getOrders() {
        return this.orderService.getOrders(
            this.searchText.getValue(),
            this.startDate.getValue() ? this.moment(this.startDate.getValue(), 'DD/MM/YYYY').format('YYYY-MM-DD HH:mm:ss') : null,
            this.endDate.getValue() ? this.moment(this.endDate.getValue(), 'DD/MM/YYYY').format('YYYY-MM-DD HH:mm:ss') : null
        ).pipe(map((orders: any) => {
            return _.map(orders, order => {
                order.date = this.moment(order.date, 'YYYY-MM-DD HH:mm:ss').format('DD/MM/YY HH:mm');
                order.subtotal = parseFloat(order.subtotal).toFixed(2);
                order.taxes = parseFloat(order.taxes).toFixed(2);
                order.total = parseFloat(order.total).toFixed(2);
                return order;
            });
        }));
    }

}
