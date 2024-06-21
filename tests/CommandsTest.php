<?php

it('Can Sync Cities', function () {
    $this->artisan('inout:sync-cities 1')->assertExitCode(0);
});

it('Can Sync Couriers', function () {
    $this->artisan('inout:sync-couriers')->assertExitCode(0);
});

it('Can Sync Counties', function () {
    $this->artisan('inout:sync-counties 1')->assertExitCode(0);
});

it('Can Sync Countries', function () {
    $this->artisan('inout:sync-countries')->assertExitCode(0);
});

it('Can Check API Status', function () {
    $this->artisan('inout:api-status')->assertExitCode(0);
});
