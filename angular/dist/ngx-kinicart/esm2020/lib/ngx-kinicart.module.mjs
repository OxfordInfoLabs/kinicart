import { NgModule } from '@angular/core';
import { TopUpComponent } from './components/top-up/top-up.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { BrowserModule } from '@angular/platform-browser';
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
export class KinicartModuleConfig {
}
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoibmd4LWtpbmljYXJ0Lm1vZHVsZS5qcyIsInNvdXJjZVJvb3QiOiIiLCJzb3VyY2VzIjpbIi4uLy4uLy4uLy4uL3Byb2plY3RzL25neC1raW5pY2FydC9zcmMvbGliL25neC1raW5pY2FydC5tb2R1bGUudHMiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IkFBQUEsT0FBTyxFQUFzQixRQUFRLEVBQUMsTUFBTSxlQUFlLENBQUM7QUFDNUQsT0FBTyxFQUFDLGNBQWMsRUFBQyxNQUFNLHNDQUFzQyxDQUFDO0FBQ3BFLE9BQU8sRUFBQyxXQUFXLEVBQUUsbUJBQW1CLEVBQUMsTUFBTSxnQkFBZ0IsQ0FBQztBQUNoRSxPQUFPLEVBQUMsZ0JBQWdCLEVBQUMsTUFBTSxzQkFBc0IsQ0FBQztBQUN0RCxPQUFPLEVBQUMsYUFBYSxFQUFDLE1BQU0sMkJBQTJCLENBQUM7O0FBaUJ4RCxNQUFNLE9BQU8saUJBQWlCO0lBQzFCLE1BQU0sQ0FBQyxPQUFPLENBQUMsSUFBMkI7UUFDdEMsT0FBTztZQUNILFFBQVEsRUFBRSxpQkFBaUI7WUFDM0IsU0FBUyxFQUFFO2dCQUNQLEVBQUMsT0FBTyxFQUFFLG9CQUFvQixFQUFFLFFBQVEsRUFBRSxJQUFJLElBQUksRUFBRSxFQUFDO2FBQ3hEO1NBQ0osQ0FBQztJQUNOLENBQUM7OytHQVJRLGlCQUFpQjtnSEFBakIsaUJBQWlCLGlCQVp0QixjQUFjLGFBR2QsV0FBVztRQUNYLG1CQUFtQjtRQUNuQixnQkFBZ0I7UUFDaEIsYUFBYSxhQUdiLGNBQWM7Z0hBR1QsaUJBQWlCLFlBVHRCLFdBQVc7UUFDWCxtQkFBbUI7UUFDbkIsZ0JBQWdCO1FBQ2hCLGFBQWE7NEZBTVIsaUJBQWlCO2tCQWQ3QixRQUFRO21CQUFDO29CQUNOLFlBQVksRUFBRTt3QkFDVixjQUFjO3FCQUNqQjtvQkFDRCxPQUFPLEVBQUU7d0JBQ0wsV0FBVzt3QkFDWCxtQkFBbUI7d0JBQ25CLGdCQUFnQjt3QkFDaEIsYUFBYTtxQkFDaEI7b0JBQ0QsT0FBTyxFQUFFO3dCQUNMLGNBQWM7cUJBQ2pCO2lCQUNKOztBQVlELE1BQU0sT0FBTyxvQkFBb0I7Q0FHaEMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQge01vZHVsZVdpdGhQcm92aWRlcnMsIE5nTW9kdWxlfSBmcm9tICdAYW5ndWxhci9jb3JlJztcbmltcG9ydCB7VG9wVXBDb21wb25lbnR9IGZyb20gJy4vY29tcG9uZW50cy90b3AtdXAvdG9wLXVwLmNvbXBvbmVudCc7XG5pbXBvcnQge0Zvcm1zTW9kdWxlLCBSZWFjdGl2ZUZvcm1zTW9kdWxlfSBmcm9tICdAYW5ndWxhci9mb3Jtcyc7XG5pbXBvcnQge0h0dHBDbGllbnRNb2R1bGV9IGZyb20gJ0Bhbmd1bGFyL2NvbW1vbi9odHRwJztcbmltcG9ydCB7QnJvd3Nlck1vZHVsZX0gZnJvbSAnQGFuZ3VsYXIvcGxhdGZvcm0tYnJvd3Nlcic7XG5cblxuQE5nTW9kdWxlKHtcbiAgICBkZWNsYXJhdGlvbnM6IFtcbiAgICAgICAgVG9wVXBDb21wb25lbnRcbiAgICBdLFxuICAgIGltcG9ydHM6IFtcbiAgICAgICAgRm9ybXNNb2R1bGUsXG4gICAgICAgIFJlYWN0aXZlRm9ybXNNb2R1bGUsXG4gICAgICAgIEh0dHBDbGllbnRNb2R1bGUsXG4gICAgICAgIEJyb3dzZXJNb2R1bGVcbiAgICBdLFxuICAgIGV4cG9ydHM6IFtcbiAgICAgICAgVG9wVXBDb21wb25lbnRcbiAgICBdXG59KVxuZXhwb3J0IGNsYXNzIE5neEtpbmljYXJ0TW9kdWxlIHtcbiAgICBzdGF0aWMgZm9yUm9vdChjb25mPzogS2luaWNhcnRNb2R1bGVDb25maWcpOiBNb2R1bGVXaXRoUHJvdmlkZXJzPE5neEtpbmljYXJ0TW9kdWxlPiB7XG4gICAgICAgIHJldHVybiB7XG4gICAgICAgICAgICBuZ01vZHVsZTogTmd4S2luaWNhcnRNb2R1bGUsXG4gICAgICAgICAgICBwcm92aWRlcnM6IFtcbiAgICAgICAgICAgICAgICB7cHJvdmlkZTogS2luaWNhcnRNb2R1bGVDb25maWcsIHVzZVZhbHVlOiBjb25mIHx8IHt9fVxuICAgICAgICAgICAgXVxuICAgICAgICB9O1xuICAgIH1cbn1cblxuZXhwb3J0IGNsYXNzIEtpbmljYXJ0TW9kdWxlQ29uZmlnIHtcbiAgICBndWVzdEh0dHBVUkw6IHN0cmluZztcbiAgICBhY2Nlc3NIdHRwVVJMOiBzdHJpbmc7XG59XG4iXX0=