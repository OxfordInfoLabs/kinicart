import {ModuleWithProviders, NgModule} from '@angular/core';
import {TopUpComponent} from './components/top-up/top-up.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {HttpClientModule} from '@angular/common/http';
import {BrowserModule} from '@angular/platform-browser';


@NgModule({
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
