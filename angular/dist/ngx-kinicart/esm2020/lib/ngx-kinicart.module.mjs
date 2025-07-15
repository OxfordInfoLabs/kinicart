import { NgModule } from '@angular/core';
import { TopUpComponent } from './components/top-up/top-up.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { BrowserModule } from '@angular/platform-browser';
import { BillingAddressComponent } from './components/billing-address/billing-address.component';
import * as i0 from "@angular/core";
export class NgxKinicartModule {
    static forRoot(conf) {
        return {
            ngModule: NgxKinicartModule,
            providers: [
                { provide: KinicartModuleConfig, useValue: conf || {} }
            ]
        };
    }
}
NgxKinicartModule.ɵfac = i0.ɵɵngDeclareFactory({ minVersion: "12.0.0", version: "14.3.0", ngImport: i0, type: NgxKinicartModule, deps: [], target: i0.ɵɵFactoryTarget.NgModule });
NgxKinicartModule.ɵmod = i0.ɵɵngDeclareNgModule({ minVersion: "14.0.0", version: "14.3.0", ngImport: i0, type: NgxKinicartModule, declarations: [TopUpComponent,
        BillingAddressComponent], imports: [FormsModule,
        ReactiveFormsModule,
        HttpClientModule,
        BrowserModule], exports: [TopUpComponent,
        BillingAddressComponent] });
NgxKinicartModule.ɵinj = i0.ɵɵngDeclareInjector({ minVersion: "12.0.0", version: "14.3.0", ngImport: i0, type: NgxKinicartModule, imports: [FormsModule,
        ReactiveFormsModule,
        HttpClientModule,
        BrowserModule] });
i0.ɵɵngDeclareClassMetadata({ minVersion: "12.0.0", version: "14.3.0", ngImport: i0, type: NgxKinicartModule, decorators: [{
            type: NgModule,
            args: [{
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
                }]
        }] });
export class KinicartModuleConfig {
}
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoibmd4LWtpbmljYXJ0Lm1vZHVsZS5qcyIsInNvdXJjZVJvb3QiOiIiLCJzb3VyY2VzIjpbIi4uLy4uLy4uLy4uL3Byb2plY3RzL25neC1raW5pY2FydC9zcmMvbGliL25neC1raW5pY2FydC5tb2R1bGUudHMiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IkFBQUEsT0FBTyxFQUFzQixRQUFRLEVBQUMsTUFBTSxlQUFlLENBQUM7QUFDNUQsT0FBTyxFQUFDLGNBQWMsRUFBQyxNQUFNLHNDQUFzQyxDQUFDO0FBQ3BFLE9BQU8sRUFBQyxXQUFXLEVBQUUsbUJBQW1CLEVBQUMsTUFBTSxnQkFBZ0IsQ0FBQztBQUNoRSxPQUFPLEVBQUMsZ0JBQWdCLEVBQUMsTUFBTSxzQkFBc0IsQ0FBQztBQUN0RCxPQUFPLEVBQUMsYUFBYSxFQUFDLE1BQU0sMkJBQTJCLENBQUM7QUFDeEQsT0FBTyxFQUFFLHVCQUF1QixFQUFFLE1BQU0sd0RBQXdELENBQUM7O0FBbUJqRyxNQUFNLE9BQU8saUJBQWlCO0lBQzFCLE1BQU0sQ0FBQyxPQUFPLENBQUMsSUFBMkI7UUFDdEMsT0FBTztZQUNILFFBQVEsRUFBRSxpQkFBaUI7WUFDM0IsU0FBUyxFQUFFO2dCQUNQLEVBQUMsT0FBTyxFQUFFLG9CQUFvQixFQUFFLFFBQVEsRUFBRSxJQUFJLElBQUksRUFBRSxFQUFDO2FBQ3hEO1NBQ0osQ0FBQztJQUNOLENBQUM7OzhHQVJRLGlCQUFpQjsrR0FBakIsaUJBQWlCLGlCQWR0QixjQUFjO1FBQ2QsdUJBQXVCLGFBR3ZCLFdBQVc7UUFDWCxtQkFBbUI7UUFDbkIsZ0JBQWdCO1FBQ2hCLGFBQWEsYUFHYixjQUFjO1FBQ2QsdUJBQXVCOytHQUdsQixpQkFBaUIsWUFWdEIsV0FBVztRQUNYLG1CQUFtQjtRQUNuQixnQkFBZ0I7UUFDaEIsYUFBYTsyRkFPUixpQkFBaUI7a0JBaEI3QixRQUFRO21CQUFDO29CQUNOLFlBQVksRUFBRTt3QkFDVixjQUFjO3dCQUNkLHVCQUF1QjtxQkFDMUI7b0JBQ0QsT0FBTyxFQUFFO3dCQUNMLFdBQVc7d0JBQ1gsbUJBQW1CO3dCQUNuQixnQkFBZ0I7d0JBQ2hCLGFBQWE7cUJBQ2hCO29CQUNELE9BQU8sRUFBRTt3QkFDTCxjQUFjO3dCQUNkLHVCQUF1QjtxQkFDMUI7aUJBQ0o7O0FBWUQsTUFBTSxPQUFPLG9CQUFvQjtDQUdoQyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7TW9kdWxlV2l0aFByb3ZpZGVycywgTmdNb2R1bGV9IGZyb20gJ0Bhbmd1bGFyL2NvcmUnO1xuaW1wb3J0IHtUb3BVcENvbXBvbmVudH0gZnJvbSAnLi9jb21wb25lbnRzL3RvcC11cC90b3AtdXAuY29tcG9uZW50JztcbmltcG9ydCB7Rm9ybXNNb2R1bGUsIFJlYWN0aXZlRm9ybXNNb2R1bGV9IGZyb20gJ0Bhbmd1bGFyL2Zvcm1zJztcbmltcG9ydCB7SHR0cENsaWVudE1vZHVsZX0gZnJvbSAnQGFuZ3VsYXIvY29tbW9uL2h0dHAnO1xuaW1wb3J0IHtCcm93c2VyTW9kdWxlfSBmcm9tICdAYW5ndWxhci9wbGF0Zm9ybS1icm93c2VyJztcbmltcG9ydCB7IEJpbGxpbmdBZGRyZXNzQ29tcG9uZW50IH0gZnJvbSAnLi9jb21wb25lbnRzL2JpbGxpbmctYWRkcmVzcy9iaWxsaW5nLWFkZHJlc3MuY29tcG9uZW50JztcblxuXG5ATmdNb2R1bGUoe1xuICAgIGRlY2xhcmF0aW9uczogW1xuICAgICAgICBUb3BVcENvbXBvbmVudCxcbiAgICAgICAgQmlsbGluZ0FkZHJlc3NDb21wb25lbnRcbiAgICBdLFxuICAgIGltcG9ydHM6IFtcbiAgICAgICAgRm9ybXNNb2R1bGUsXG4gICAgICAgIFJlYWN0aXZlRm9ybXNNb2R1bGUsXG4gICAgICAgIEh0dHBDbGllbnRNb2R1bGUsXG4gICAgICAgIEJyb3dzZXJNb2R1bGVcbiAgICBdLFxuICAgIGV4cG9ydHM6IFtcbiAgICAgICAgVG9wVXBDb21wb25lbnQsXG4gICAgICAgIEJpbGxpbmdBZGRyZXNzQ29tcG9uZW50XG4gICAgXVxufSlcbmV4cG9ydCBjbGFzcyBOZ3hLaW5pY2FydE1vZHVsZSB7XG4gICAgc3RhdGljIGZvclJvb3QoY29uZj86IEtpbmljYXJ0TW9kdWxlQ29uZmlnKTogTW9kdWxlV2l0aFByb3ZpZGVyczxOZ3hLaW5pY2FydE1vZHVsZT4ge1xuICAgICAgICByZXR1cm4ge1xuICAgICAgICAgICAgbmdNb2R1bGU6IE5neEtpbmljYXJ0TW9kdWxlLFxuICAgICAgICAgICAgcHJvdmlkZXJzOiBbXG4gICAgICAgICAgICAgICAge3Byb3ZpZGU6IEtpbmljYXJ0TW9kdWxlQ29uZmlnLCB1c2VWYWx1ZTogY29uZiB8fCB7fX1cbiAgICAgICAgICAgIF1cbiAgICAgICAgfTtcbiAgICB9XG59XG5cbmV4cG9ydCBjbGFzcyBLaW5pY2FydE1vZHVsZUNvbmZpZyB7XG4gICAgZ3Vlc3RIdHRwVVJMOiBzdHJpbmc7XG4gICAgYWNjZXNzSHR0cFVSTDogc3RyaW5nO1xufVxuIl19