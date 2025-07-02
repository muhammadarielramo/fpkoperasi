import { TestBed } from '@angular/core/testing';

import { MockLocationService } from './mock-location.service';

describe('MockLocationService', () => {
  let service: MockLocationService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(MockLocationService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
