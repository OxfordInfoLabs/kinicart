import { TestBed } from '@angular/core/testing';

import { PaymentService } from './payment.service';
import { NgKinibindModule } from 'ng-kinibind';
import { KinicartModuleConfig, NgKinicartModule } from '../../ng-kinicart.module';

describe('PaymentService', () => {
    const config: KinicartModuleConfig = {
        guestHttpURL: 'string',
        accessHttpURL: 'string'
    };
    beforeEach(() => TestBed.configureTestingModule({
        imports: [
            NgKinibindModule
        ],
        providers: [
            { provide: KinicartModuleConfig, useValue: config }
        ]
    }));

    it('should be created', () => {
        // const service: PaymentService = TestBed.get(PaymentService);
        // expect(service).toBeTruthy();
    });
});
