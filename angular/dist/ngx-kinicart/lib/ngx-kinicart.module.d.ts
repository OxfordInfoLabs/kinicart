import { ModuleWithProviders } from '@angular/core';
import * as i0 from "@angular/core";
import * as i1 from "./components/top-up/top-up.component";
import * as i2 from "./components/billing-address/billing-address.component";
import * as i3 from "@angular/forms";
import * as i4 from "@angular/common/http";
import * as i5 from "@angular/platform-browser";
export declare class NgxKinicartModule {
    static forRoot(conf?: KinicartModuleConfig): ModuleWithProviders<NgxKinicartModule>;
    static ɵfac: i0.ɵɵFactoryDeclaration<NgxKinicartModule, never>;
    static ɵmod: i0.ɵɵNgModuleDeclaration<NgxKinicartModule, [typeof i1.TopUpComponent, typeof i2.BillingAddressComponent], [typeof i3.FormsModule, typeof i3.ReactiveFormsModule, typeof i4.HttpClientModule, typeof i5.BrowserModule], [typeof i1.TopUpComponent, typeof i2.BillingAddressComponent]>;
    static ɵinj: i0.ɵɵInjectorDeclaration<NgxKinicartModule>;
}
export declare class KinicartModuleConfig {
    guestHttpURL: string;
    accessHttpURL: string;
}
