import { KinicartModuleConfig } from '../ngx-kinicart.module';
import { HttpClient } from '@angular/common/http';
import * as i0 from "@angular/core";
export declare class CartService {
    private config;
    private http;
    constructor(config: KinicartModuleConfig, http: HttpClient);
    addTopUpCartItem(amount: any): Promise<Object>;
    static ɵfac: i0.ɵɵFactoryDeclaration<CartService, never>;
    static ɵprov: i0.ɵɵInjectableDeclaration<CartService>;
}
