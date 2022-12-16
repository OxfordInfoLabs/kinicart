import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {HomeComponent} from './views/home/home.component';
import {AccountsComponent} from './views/accounts/accounts.component';
import {TopUpComponent} from './views/accounts/top-up/top-up.component';
import {OrdersComponent} from './views/accounts/orders/orders.component';
import {LoginComponent} from './views/login/login.component';
import {AuthGuard} from './guards/auth.guard';

const routes: Routes = [
    {
        path: '',
        redirectTo: 'home',
        pathMatch: 'full'
    },
    {
        path: 'home',
        component: HomeComponent,
        canActivate: [AuthGuard]
    },
    {
        path: 'account',
        component: AccountsComponent,
        canActivate: [AuthGuard]
    },
    {
        path:'account/top-up',
        component: TopUpComponent,
        canActivate: [AuthGuard]
    },
    {
        path:'account/top-up/:status',
        component: TopUpComponent,
        canActivate: [AuthGuard]
    },
    {
        path: 'account/orders',
        component: OrdersComponent,
        canActivate: [AuthGuard]
    },
    {
        path: 'login',
        component: LoginComponent
    }
];

@NgModule({
    imports: [RouterModule.forRoot(routes)],
    exports: [RouterModule]
})
export class AppRoutingModule {
}
