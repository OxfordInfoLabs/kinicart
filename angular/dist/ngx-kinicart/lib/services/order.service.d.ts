import { KinicartModuleConfig } from '../ngx-kinicart.module';
import { HttpClient } from '@angular/common/http';
import * as i0 from "@angular/core";
export declare class OrderService {
    private config;
    private http;
    constructor(config: KinicartModuleConfig, http: HttpClient);
    getOrders(searchTerm?: any, startDate?: any, endDate?: any): import("rxjs").Observable<Object>;
    static ɵfac: i0.ɵɵFactoryDeclaration<OrderService, never>;
    static ɵprov: i0.ɵɵInjectableDeclaration<OrderService>;
}
