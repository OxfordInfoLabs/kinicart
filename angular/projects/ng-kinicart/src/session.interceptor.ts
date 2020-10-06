import { Injectable } from '@angular/core';
import {
    HttpRequest,
    HttpHandler,
    HttpInterceptor, HttpErrorResponse
} from '@angular/common/http';
import { throwError } from 'rxjs/internal/observable/throwError';
import { finalize, tap } from 'rxjs/operators';
import { KinicartModuleConfig } from './ng-kinicart.module';
import * as _ from 'lodash';

@Injectable()
export class SessionInterceptor implements HttpInterceptor {

    constructor(private config: KinicartModuleConfig) {
    }

    intercept(request: HttpRequest<any>, next: HttpHandler) {
        request = request.clone({
            withCredentials: true
        });

        if (!request.url.startsWith('http')) {
            request = request.clone({
                url: this.config.accessHttpURL + request.url
            });
        }

        if (!request.headers.has('Content-Type')) {
            request = request.clone({ headers: request.headers.set('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8') });
        }

        const session = sessionStorage.getItem('sessionData');
        if (session && _.filter(JSON.parse(session)).length) {
            const sessionData = JSON.parse(session);
            if (sessionData && sessionData.csrfToken) {
                request = request.clone({ headers: request.headers.set('x-csrf-token', sessionData.csrfToken) });
            }
        }

        return next.handle(request)
            .pipe(
                tap(
                    event => {
                    },
                    error => {
                        if (error instanceof HttpErrorResponse) {
                            return throwError(error);
                        }
                    }
                ),
                finalize(() => {
                })
            );
    }

}
