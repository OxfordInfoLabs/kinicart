import {ModuleWithProviders, NgModule} from '@angular/core';
import {TopUpComponent} from './components/top-up/top-up.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {HttpClientModule} from '@angular/common/http';
import {BrowserModule} from '@angular/platform-browser';
import { BillingAddressComponent } from './components/billing-address/billing-address.component';


@NgModule({
    declarations: [
        TopUpComponent,
        BillingAddressComponent
    ],
    imports: [
        FormsModule,
        ReactiveFormsModule,
        HttpClientModule,
        BrowserModule
    ],
    exports: [
        TopUpComponent,
        BillingAddressComponent
    ]
})
export class NgxKinicartModule {
    static forRoot(conf?: KinicartModuleConfig): ModuleWithProviders<NgxKinicartModule> {
        return {
            ngModule: NgxKinicartModule,
            providers: [
                {provide: KinicartModuleConfig, useValue: conf || {}}
            ]
        };
    }
}

export class KinicartModuleConfig {
    guestHttpURL: string;
    accessHttpURL: string;
}
