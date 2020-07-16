import { TestBed } from '@angular/core/testing';

import { OrderService } from './order.service';
import { KinicartModuleConfig } from '../../ng-kinicart.module';
import { NgKinibindModule } from 'ng-kinibind';

describe('OrderService', () => {
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
        // const service: OrderService = TestBed.get(OrderService);
        // expect(service).toBeTruthy();
    });
});
