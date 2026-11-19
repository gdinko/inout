<?php

use Mchervenkov\Inout\Inout;
use Mchervenkov\Inout\Models\CarrierCityMap;
use Mchervenkov\Inout\Models\InoutCity;
use Mchervenkov\Inout\Models\InoutCompanyCourier;
use Mchervenkov\Inout\Models\InoutCountry;
use Mchervenkov\Inout\Models\InoutCourierOffice;

beforeEach(function (): void {
    InoutCompanyCourier::create([
        'courier_id' => 19, 'name' => 'Greece ACS', 'signature' => 'acs',
        'to_office' => 1, 'to_address' => 1, 'country' => 'GR', 'currency' => 'EUR',
    ]);
    InoutCountry::create([
        'country_id' => 1, 'name' => 'Greece', 'cyrillic_name' => 'Greece', 'iso_code' => 'GR',
    ]);
    CarrierCityMap::create([
        'carrier_signature' => Inout::SIGNATURE, 'carrier_city_id' => 1,
        'country_code' => 'GR', 'name' => 'Old', 'name_slug' => 'old',
        'post_code' => '100', 'slug' => 'old-100', 'uuid' => 'old-uuid',
    ]);
    InoutCourierOffice::create([
        'office_id' => 504967, 'city_id' => 21961326, 'courier_id' => 19,
        'courier_office_id' => null, 'group_city_id' => 1, 'office_name' => 'Test',
        'city_uuid' => 'old-uuid',
    ]);
});

it('reports missing city identifiers and preserves existing mappings', function (): void {
    $this->artisan('inout:map-cities', ['courier_id' => 19, 'country_code' => 'gr'])
        ->expectsOutput('Missing city: office_id=504967, city_id=21961326. Sync cities before mapping.')
        ->assertExitCode(1);

    expect(CarrierCityMap::first()->name)->toBe('Old');
    expect(InoutCourierOffice::first()->city_uuid)->toBe('old-uuid');
});

it('maps cities with a lowercase country code', function (): void {
    InoutCity::create(['city_id' => 21961326, 'name_en' => 'Athens', 'postal_code' => '100']);

    $this->artisan('inout:map-cities', ['courier_id' => 19, 'country_code' => 'gr'])
        ->assertExitCode(0);

    expect(CarrierCityMap::first()->name)->toBe('Athens');
    expect(InoutCourierOffice::first()->city_uuid)->toBe(CarrierCityMap::first()->uuid);
});

it('rolls back mappings when saving an office fails', function (): void {
    InoutCity::create(['city_id' => 21961326, 'name_en' => 'Athens', 'postal_code' => '100']);
    InoutCourierOffice::updating(function (): void {
        throw new RuntimeException('Office update failed');
    });

    try {
        $this->artisan('inout:map-cities', ['courier_id' => 19, 'country_code' => 'GR'])
            ->expectsOutput('Office update failed')
            ->assertExitCode(1);
    } finally {
        InoutCourierOffice::flushEventListeners();
    }

    expect(CarrierCityMap::first()->name)->toBe('Old');
    expect(InoutCourierOffice::first()->city_uuid)->toBe('old-uuid');
});
