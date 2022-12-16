import {NgModule} from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';

import {AppRoutingModule} from './app-routing.module';
import {AppComponent} from './app.component';
import {NgxKinicartModule} from 'ngx-kinicart';
import { AccountsComponent } from './views/accounts/accounts.component';
import { HomeComponent } from './views/home/home.component';
import { TopUpComponent } from './views/accounts/top-up/top-up.component';
import { OrdersComponent } from './views/accounts/orders/orders.component';
import {environment} from '../environments/environment';
import {NgKiniAuthModule} from 'ng-kiniauth';
import {NgKinibindModule} from 'ng-kinibind';
import { LoginComponent } from './views/login/login.component';
import {HTTP_INTERCEPTORS} from '@angular/common/http';
import {SessionInterceptor} from './session.interceptor';
import {MatSnackBarModule} from '@angular/material/snack-bar';

@NgModule({
  declarations: [
    AppComponent,
    AccountsComponent,
    HomeComponent,
    TopUpComponent,
    OrdersComponent,
    LoginComponent
  ],
    imports: [
        BrowserModule,
        AppRoutingModule,
        NgxKinicartModule.forRoot({
            guestHttpURL: `${environment.backendURL}/guest`,
            accessHttpURL: `${environment.backendURL}/account`
        }),
        NgKiniAuthModule.forRoot({
            guestHttpURL: `${environment.backendURL}/guest`,
            accessHttpURL: `${environment.backendURL}/account`
        }),
        NgKinibindModule,
        MatSnackBarModule
    ],
  providers: [
      {
          provide: HTTP_INTERCEPTORS,
          useClass: SessionInterceptor,
          multi: true
      }
  ],
  bootstrap: [AppComponent]
})
export class AppModule {
}
