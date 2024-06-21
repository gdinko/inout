<?php


use Mchervenkov\Inout\Inout;

test('setup default inout object', function () {

    $inout = new Inout();

    expect($inout)->toBeInstanceOf(Inout::class);

    expect($inout->getApiToken())->toEqual(config('inout.api_token'));

    $defaultBaseUrl = config('inout.production_base_api_url');

    if (config('inout.env') == 'test') {
        $defaultBaseUrl = config('inout.test_base_url');
    }

    expect($inout->getBaseUrl())->toEqual($defaultBaseUrl);

    expect($inout->getTimeout())->toEqual(config('inout.timeout'));
});

test('set props of inout object', function () {
    $inout = new Inout();

    expect($inout)->toBeInstanceOf(Inout::class);

    $inout->setTestMode(1);

    expect($inout->getTestMode())->toEqual(1);

    $inout->setBaseUrl('endpoint');

    expect($inout->getBaseUrl())->toEqual('endpoint');

    $inout->setTimeout(99);

    expect($inout->getTimeout())->toEqual(99);

    $inout->setCompanyId(100);

    expect($inout->getCompanyId())->toEqual(100);
});

test('set test endpoint of inout object', function () {
    config(['inout.env' => 'test']);

    $inout = new Inout();

    expect($inout->getBaseUrl())->toEqual(config('inout.test_base_url'));
});

test('set production endpoint of inout object', function () {
    config(['inout.env' => 'production']);

    $inout = new Inout();

    expect($inout->getBaseUrl())->toEqual(config('inout.production_base_url'));
});
