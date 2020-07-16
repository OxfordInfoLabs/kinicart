import { ModuleWithProviders, NgModule } from '@angular/core';
import { NgKinibindModule } from 'ng-kinibind';
import { RouterModule } from '@angular/router';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { InlineModalComponent } from './lib/views/inline-modal/inline-modal.component';
import { PaymentMethodsComponent } from './lib/views/payment-methods/payment-methods.component';
import { PaymentMethodComponent } from './lib/views/payment-methods/payment-method/payment-method.component';
import { OrderHistoryComponent } from './lib/views/order-history/order-history.component';
import { MatPaginatorModule } from '@angular/material/paginator';
import { MatDatepickerModule } from '@angular/material/datepicker';
import { OrderDetailsComponent } from './lib/views/order-history/order-details/order-details.component';
import { MatCheckboxModule } from '@angular/material/checkbox';

@NgModule({
    declarations: [
        InlineModalComponent,
        PaymentMethodsComponent,
        PaymentMethodComponent,
        OrderHistoryComponent,
        OrderDetailsComponent
    ],
    imports: [
        NgKinibindModule,
        RouterModule,
        CommonModule,
        FormsModule,
        ReactiveFormsModule,
        MatPaginatorModule,
        MatDatepickerModule,
        MatCheckboxModule
    ],
    exports: [
        PaymentMethodsComponent,
        OrderHistoryComponent
    ],
    providers: [
    ]
})
export class NgKinicartModule {

    static forRoot(conf?: KinicartModuleConfig): ModuleWithProviders {
        return {
            ngModule: NgKinicartModule,
            providers: [
                { provide: KinicartModuleConfig, useValue: conf || {} }
            ]
        };
    }
}

export class KinicartModuleConfig {
    guestHttpURL: string;
    accessHttpURL: string;
}
