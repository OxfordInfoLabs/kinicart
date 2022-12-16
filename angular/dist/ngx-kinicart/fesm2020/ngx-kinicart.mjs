import * as i0 from '@angular/core';
import { Component, NgModule, Injectable } from '@angular/core';
import * as i2 from '@angular/router';
import * as i3 from '@angular/forms';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import * as i4 from '@angular/common';
import * as i2$1 from '@angular/common/http';
import { HttpClientModule } from '@angular/common/http';
import { BrowserModule } from '@angular/platform-browser';
import * as _ from 'lodash';

class TopUpComponent {
    constructor(paymentService, route) {
        this.paymentService = paymentService;
        this.route = route;
    }
    ngOnInit() {
        this.route.params.subscribe(params => {
            this.status = params.status || '';
        });
    }
    async topUp() {
        if (this.topUpAmount >= 5) {
            const lineItem = {
                price_data: {
                    currency: 'gbp',
                    unit_amount: this.topUpAmount * 100,
                    product_data: {
                        name: 'Account Top Up'
                    }
                },
                quantity: 1
            };
            const checkoutSession = await this.paymentService.getStripeCheckoutSessionURL([lineItem], 'payment', window.location.origin + '/account/top-up/cancel', window.location.origin + '/account/top-up/success');
            window.location.href = checkoutSession;
        }
        else {
            window.alert('Please enter a top up amount greater than 5.');
        }
    }
    viewOrder() {
    }
}
TopUpComponent.ɵfac = i0.ɵɵngDeclareFactory({ minVersion: "12.0.0", version: "14.2.12", ngImport: i0, type: TopUpComponent, deps: [{ token: PaymentService }, { token: i2.ActivatedRoute }], target: i0.ɵɵFactoryTarget.Component });
TopUpComponent.ɵcmp = i0.ɵɵngDeclareComponent({ minVersion: "14.0.0", version: "14.2.12", type: TopUpComponent, selector: "kc-top-up", ngImport: i0, template: "<div class=\"space-y-6\">\n    <div *ngIf=\"status === 'cancel'\" class=\"shadow rounded-md bg-yellow-50 p-4\">\n        <div class=\"flex\">\n            <div class=\"flex-shrink-0\">\n                <!-- Heroicon name: mini/exclamation-triangle -->\n                <svg class=\"h-5 w-5 text-yellow-400\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 20 20\" fill=\"currentColor\" aria-hidden=\"true\">\n                    <path fill-rule=\"evenodd\" d=\"M8.485 3.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 3.495zM10 6a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 6zm0 9a1 1 0 100-2 1 1 0 000 2z\" clip-rule=\"evenodd\" />\n                </svg>\n            </div>\n            <div class=\"ml-3\">\n                <h3 class=\"text-sm font-medium text-yellow-800\">Checkout cancelled</h3>\n                <div class=\"mt-2 text-sm text-yellow-700\">\n                    <p>Top up was cancelled during checkout. Please proceed to payment to complete the top up.</p>\n                </div>\n            </div>\n            <div class=\"ml-auto pl-3\">\n                <div class=\"-mx-1.5 -my-1.5\">\n                    <button type=\"button\" (click)=\"status = ''\"\n                            class=\"inline-flex rounded-md bg-yellow-50 p-1.5 text-yellow-500 hover:bg-yellow-100 \">\n                        <span class=\"sr-only\">Dismiss</span>\n                        <!-- Heroicon name: mini/x-mark -->\n                        <svg class=\"h-5 w-5\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 20 20\" fill=\"currentColor\" aria-hidden=\"true\">\n                            <path d=\"M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z\" />\n                        </svg>\n                    </button>\n                </div>\n            </div>\n        </div>\n    </div>\n    <div *ngIf=\"status === 'success'\" class=\"shadow rounded-md bg-green-50 p-4\">\n        <div class=\"flex\">\n            <div class=\"flex-shrink-0\">\n                <!-- Heroicon name: mini/check-circle -->\n                <svg class=\"h-5 w-5 text-green-400\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 20 20\" fill=\"currentColor\" aria-hidden=\"true\">\n                    <path fill-rule=\"evenodd\" d=\"M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z\" clip-rule=\"evenodd\" />\n                </svg>\n            </div>\n            <div class=\"ml-3\">\n                <h3 class=\"text-sm font-medium text-green-800\">Top up completed</h3>\n                <div class=\"mt-2 text-sm text-green-700\">\n                    <p>Click view order to see the completed order.</p>\n                </div>\n                <div class=\"mt-4\">\n                    <div class=\"-mx-2 -my-1.5 flex\">\n                        <button type=\"button\" (click)=\"viewOrder()\"\n                                class=\"rounded-md bg-green-50 px-2 py-1.5 text-sm font-medium text-green-800 hover:bg-green-100\">\n                            View order</button>\n                    </div>\n                </div>\n            </div>\n            <div class=\"ml-auto pl-3\">\n                <div class=\"-mx-1.5 -my-1.5\">\n                    <button type=\"button\" (click)=\"status = ''\"\n                            class=\"inline-flex rounded-md bg-green-50 p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 focus:ring-offset-green-50\">\n                        <span class=\"sr-only\">Dismiss</span>\n                        <!-- Heroicon name: mini/x-mark -->\n                        <svg class=\"h-5 w-5\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 20 20\" fill=\"currentColor\" aria-hidden=\"true\">\n                            <path d=\"M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z\" />\n                        </svg>\n                    </button>\n                </div>\n            </div>\n        </div>\n    </div>\n    <div class=\"bg-white px-4 py-5 shadow sm:rounded-lg sm:p-6\">\n        <div class=\"md:grid md:grid-cols-3 md:gap-6\">\n            <div class=\"md:col-span-1\">\n                <h3 class=\"text-lg font-medium leading-6 text-gray-900\">Top Up</h3>\n                <p class=\"mt-1 text-sm text-gray-500\">Enter the amount you would like to add to your account.</p>\n            </div>\n            <div class=\"mt-5 space-y-6 md:col-span-2 md:mt-0\">\n                <div class=\"grid grid-cols-3 gap-6\">\n                    <div class=\"col-span-3 sm:col-span-2\">\n                        <label class=\"block text-sm font-medium text-gray-700\">Top Up Amount</label>\n                        <div class=\"mt-1 flex rounded-md shadow-sm\">\n                            <span\n                                class=\"inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-50 px-3 text-sm text-gray-500\">\n                                \u00A3</span>\n                            <input type=\"number\" [(ngModel)]=\"topUpAmount\"\n                                   class=\"block w-full flex-1 rounded-none rounded-r-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm\"\n                                   placeholder=\"Enter amount\">\n                        </div>\n                    </div>\n                </div>\n\n                <div class=\"flex justify-end\">\n                    <button type=\"button\" class=\"rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50\">\n                        Cancel</button>\n                    <button type=\"button\" (click)=\"topUp()\"\n                            class=\"ml-3 inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700\">\n                        Proceed to Payment</button>\n                </div>\n            </div>\n        </div>\n    </div>\n</div>\n", styles: [""], dependencies: [{ kind: "directive", type: i3.DefaultValueAccessor, selector: "input:not([type=checkbox])[formControlName],textarea[formControlName],input:not([type=checkbox])[formControl],textarea[formControl],input:not([type=checkbox])[ngModel],textarea[ngModel],[ngDefaultControl]" }, { kind: "directive", type: i3.NumberValueAccessor, selector: "input[type=number][formControlName],input[type=number][formControl],input[type=number][ngModel]" }, { kind: "directive", type: i3.NgControlStatus, selector: "[formControlName],[ngModel],[formControl]" }, { kind: "directive", type: i3.NgModel, selector: "[ngModel]:not([formControlName]):not([formControl])", inputs: ["name", "disabled", "ngModel", "ngModelOptions"], outputs: ["ngModelChange"], exportAs: ["ngModel"] }, { kind: "directive", type: i4.NgIf, selector: "[ngIf]", inputs: ["ngIf", "ngIfThen", "ngIfElse"] }] });
i0.ɵɵngDeclareClassMetadata({ minVersion: "12.0.0", version: "14.2.12", ngImport: i0, type: TopUpComponent, decorators: [{
            type: Component,
            args: [{ selector: 'kc-top-up', template: "<div class=\"space-y-6\">\n    <div *ngIf=\"status === 'cancel'\" class=\"shadow rounded-md bg-yellow-50 p-4\">\n        <div class=\"flex\">\n            <div class=\"flex-shrink-0\">\n                <!-- Heroicon name: mini/exclamation-triangle -->\n                <svg class=\"h-5 w-5 text-yellow-400\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 20 20\" fill=\"currentColor\" aria-hidden=\"true\">\n                    <path fill-rule=\"evenodd\" d=\"M8.485 3.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 3.495zM10 6a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 6zm0 9a1 1 0 100-2 1 1 0 000 2z\" clip-rule=\"evenodd\" />\n                </svg>\n            </div>\n            <div class=\"ml-3\">\n                <h3 class=\"text-sm font-medium text-yellow-800\">Checkout cancelled</h3>\n                <div class=\"mt-2 text-sm text-yellow-700\">\n                    <p>Top up was cancelled during checkout. Please proceed to payment to complete the top up.</p>\n                </div>\n            </div>\n            <div class=\"ml-auto pl-3\">\n                <div class=\"-mx-1.5 -my-1.5\">\n                    <button type=\"button\" (click)=\"status = ''\"\n                            class=\"inline-flex rounded-md bg-yellow-50 p-1.5 text-yellow-500 hover:bg-yellow-100 \">\n                        <span class=\"sr-only\">Dismiss</span>\n                        <!-- Heroicon name: mini/x-mark -->\n                        <svg class=\"h-5 w-5\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 20 20\" fill=\"currentColor\" aria-hidden=\"true\">\n                            <path d=\"M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z\" />\n                        </svg>\n                    </button>\n                </div>\n            </div>\n        </div>\n    </div>\n    <div *ngIf=\"status === 'success'\" class=\"shadow rounded-md bg-green-50 p-4\">\n        <div class=\"flex\">\n            <div class=\"flex-shrink-0\">\n                <!-- Heroicon name: mini/check-circle -->\n                <svg class=\"h-5 w-5 text-green-400\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 20 20\" fill=\"currentColor\" aria-hidden=\"true\">\n                    <path fill-rule=\"evenodd\" d=\"M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z\" clip-rule=\"evenodd\" />\n                </svg>\n            </div>\n            <div class=\"ml-3\">\n                <h3 class=\"text-sm font-medium text-green-800\">Top up completed</h3>\n                <div class=\"mt-2 text-sm text-green-700\">\n                    <p>Click view order to see the completed order.</p>\n                </div>\n                <div class=\"mt-4\">\n                    <div class=\"-mx-2 -my-1.5 flex\">\n                        <button type=\"button\" (click)=\"viewOrder()\"\n                                class=\"rounded-md bg-green-50 px-2 py-1.5 text-sm font-medium text-green-800 hover:bg-green-100\">\n                            View order</button>\n                    </div>\n                </div>\n            </div>\n            <div class=\"ml-auto pl-3\">\n                <div class=\"-mx-1.5 -my-1.5\">\n                    <button type=\"button\" (click)=\"status = ''\"\n                            class=\"inline-flex rounded-md bg-green-50 p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 focus:ring-offset-green-50\">\n                        <span class=\"sr-only\">Dismiss</span>\n                        <!-- Heroicon name: mini/x-mark -->\n                        <svg class=\"h-5 w-5\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 20 20\" fill=\"currentColor\" aria-hidden=\"true\">\n                            <path d=\"M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z\" />\n                        </svg>\n                    </button>\n                </div>\n            </div>\n        </div>\n    </div>\n    <div class=\"bg-white px-4 py-5 shadow sm:rounded-lg sm:p-6\">\n        <div class=\"md:grid md:grid-cols-3 md:gap-6\">\n            <div class=\"md:col-span-1\">\n                <h3 class=\"text-lg font-medium leading-6 text-gray-900\">Top Up</h3>\n                <p class=\"mt-1 text-sm text-gray-500\">Enter the amount you would like to add to your account.</p>\n            </div>\n            <div class=\"mt-5 space-y-6 md:col-span-2 md:mt-0\">\n                <div class=\"grid grid-cols-3 gap-6\">\n                    <div class=\"col-span-3 sm:col-span-2\">\n                        <label class=\"block text-sm font-medium text-gray-700\">Top Up Amount</label>\n                        <div class=\"mt-1 flex rounded-md shadow-sm\">\n                            <span\n                                class=\"inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-50 px-3 text-sm text-gray-500\">\n                                \u00A3</span>\n                            <input type=\"number\" [(ngModel)]=\"topUpAmount\"\n                                   class=\"block w-full flex-1 rounded-none rounded-r-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm\"\n                                   placeholder=\"Enter amount\">\n                        </div>\n                    </div>\n                </div>\n\n                <div class=\"flex justify-end\">\n                    <button type=\"button\" class=\"rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50\">\n                        Cancel</button>\n                    <button type=\"button\" (click)=\"topUp()\"\n                            class=\"ml-3 inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700\">\n                        Proceed to Payment</button>\n                </div>\n            </div>\n        </div>\n    </div>\n</div>\n" }]
        }], ctorParameters: function () { return [{ type: PaymentService }, { type: i2.ActivatedRoute }]; } });

class NgxKinicartModule {
    static forRoot(conf) {
        return {
            ngModule: NgxKinicartModule,
            providers: [
                { provide: KinicartModuleConfig, useValue: conf || {} }
            ]
        };
    }
}
NgxKinicartModule.ɵfac = i0.ɵɵngDeclareFactory({ minVersion: "12.0.0", version: "14.2.12", ngImport: i0, type: NgxKinicartModule, deps: [], target: i0.ɵɵFactoryTarget.NgModule });
NgxKinicartModule.ɵmod = i0.ɵɵngDeclareNgModule({ minVersion: "14.0.0", version: "14.2.12", ngImport: i0, type: NgxKinicartModule, declarations: [TopUpComponent], imports: [FormsModule,
        ReactiveFormsModule,
        HttpClientModule,
        BrowserModule], exports: [TopUpComponent] });
NgxKinicartModule.ɵinj = i0.ɵɵngDeclareInjector({ minVersion: "12.0.0", version: "14.2.12", ngImport: i0, type: NgxKinicartModule, imports: [FormsModule,
        ReactiveFormsModule,
        HttpClientModule,
        BrowserModule] });
i0.ɵɵngDeclareClassMetadata({ minVersion: "12.0.0", version: "14.2.12", ngImport: i0, type: NgxKinicartModule, decorators: [{
            type: NgModule,
            args: [{
                    declarations: [
                        TopUpComponent
                    ],
                    imports: [
                        FormsModule,
                        ReactiveFormsModule,
                        HttpClientModule,
                        BrowserModule
                    ],
                    exports: [
                        TopUpComponent
                    ]
                }]
        }] });
class KinicartModuleConfig {
}

class PaymentService {
    constructor(config, http) {
        this.config = config;
        this.http = http;
    }
    getStripeCheckoutSessionURL(lineItems = [], mode = 'payment', cancelURL = '/cancel', successURL = '/success', currency = 'gbp') {
        return this.http.post(this.config.accessHttpURL + '/stripe/checkoutSession', {
            lineItems, mode, cancelURL, successURL, currency
        }).toPromise().then((sessionURL) => {
            return sessionURL;
        });
    }
    removePaymentMethod(methodId, type) {
        return this.http.get(this.config.accessHttpURL + '/payment/remove', {
            params: { methodId, provider: type }
        }).toPromise();
    }
}
PaymentService.ɵfac = i0.ɵɵngDeclareFactory({ minVersion: "12.0.0", version: "14.2.12", ngImport: i0, type: PaymentService, deps: [{ token: KinicartModuleConfig }, { token: i2$1.HttpClient }], target: i0.ɵɵFactoryTarget.Injectable });
PaymentService.ɵprov = i0.ɵɵngDeclareInjectable({ minVersion: "12.0.0", version: "14.2.12", ngImport: i0, type: PaymentService, providedIn: 'root' });
i0.ɵɵngDeclareClassMetadata({ minVersion: "12.0.0", version: "14.2.12", ngImport: i0, type: PaymentService, decorators: [{
            type: Injectable,
            args: [{
                    providedIn: 'root'
                }]
        }], ctorParameters: function () { return [{ type: KinicartModuleConfig }, { type: i2$1.HttpClient }]; } });

class OrderService {
    constructor(config, http) {
        this.config = config;
        this.http = http;
    }
    getOrders(searchTerm, startDate, endDate) {
        return this.http.post(this.config.accessHttpURL + '/order/orders', _.pickBy({ searchTerm, startDate, endDate }, _.identity));
    }
}
OrderService.ɵfac = i0.ɵɵngDeclareFactory({ minVersion: "12.0.0", version: "14.2.12", ngImport: i0, type: OrderService, deps: [{ token: KinicartModuleConfig }, { token: i2$1.HttpClient }], target: i0.ɵɵFactoryTarget.Injectable });
OrderService.ɵprov = i0.ɵɵngDeclareInjectable({ minVersion: "12.0.0", version: "14.2.12", ngImport: i0, type: OrderService, providedIn: 'root' });
i0.ɵɵngDeclareClassMetadata({ minVersion: "12.0.0", version: "14.2.12", ngImport: i0, type: OrderService, decorators: [{
            type: Injectable,
            args: [{
                    providedIn: 'root'
                }]
        }], ctorParameters: function () { return [{ type: KinicartModuleConfig }, { type: i2$1.HttpClient }]; } });

/*
 * Public API Surface of ngx-kinicart
 */

/**
 * Generated bundle index. Do not edit.
 */

export { KinicartModuleConfig, NgxKinicartModule, OrderService, PaymentService, TopUpComponent };
//# sourceMappingURL=ngx-kinicart.mjs.map
