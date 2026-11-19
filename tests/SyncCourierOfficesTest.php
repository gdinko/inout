<?php

use Illuminate\Support\Facades\Http;
use Mchervenkov\Inout\Models\InoutCompanyCourier;
use Mchervenkov\Inout\Models\InoutCourierOffice;

it('syncs offices without a subcontractor identifier and updates them without duplicates', function (): void {
    InoutCompanyCourier::create([
        'courier_id' => 653, 'name' => 'Test', 'signature' => 'test',
        'to_office' => 1, 'to_address' => 1, 'country' => 'GR', 'currency' => 'EUR',
    ]);

    $office = [
        'ID' => 504967, 'CITY_ID' => 21961326, 'COURIER_ID' => 653,
        'COURIER_OFFICE_ID' => null, 'GROUP_CITY_ID' => 21349030,
        'OFFICE_NAME' => 'Original',
    ];

    Http::fake(['*' => Http::response([$office])]);
    $this->artisan('inout:sync-courier-offices', ['courier_id' => 653])->assertExitCode(0);

    $office['OFFICE_NAME'] = 'Updated';
    Http::fake(['*' => Http::response([$office])]);
    $this->artisan('inout:sync-courier-offices', ['courier_id' => 653])->assertExitCode(0);

    expect(InoutCourierOffice::count())->toBe(1);
    expect(InoutCourierOffice::first()->office_name)->toBe('Updated');
    expect(InoutCourierOffice::first()->courier_office_id)->toBeNull();
});

it('clears only stale offices of the selected courier and preserves couriers', function (): void {
    InoutCompanyCourier::create([
        'courier_id' => 653, 'name' => 'Test', 'signature' => 'test',
        'to_office' => 1, 'to_address' => 1, 'country' => 'GR', 'currency' => 'EUR',
    ]);

    foreach ([[1, 653, 10], [2, 654, 10], [3, 653, 0]] as [$officeId, $courierId, $days]) {
        InoutCourierOffice::create([
            'office_id' => $officeId, 'courier_id' => $courierId,
            'city_id' => 1, 'group_city_id' => 1, 'office_name' => 'Test',
            'courier_office_id' => null,
        ])->forceFill([
            'created_at' => now()->subDays(20),
            'updated_at' => now()->subDays($days),
        ])->save();
    }

    Http::fake(['*' => Http::response([])]);
    $this->artisan('inout:sync-courier-offices', ['courier_id' => 653, '--clear' => 5])
        ->assertExitCode(0);

    expect(InoutCourierOffice::orderBy('office_id')->pluck('office_id')->all())->toBe([2, 3]);
    expect(InoutCompanyCourier::count())->toBe(1);
});
