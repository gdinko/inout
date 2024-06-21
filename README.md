# Laravel Inout API Wrapper

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mchervenkov/inout.svg?style=flat-square)](https://packagist.org/packages/mchervenkov/inout)
[![Total Downloads](https://img.shields.io/packagist/dt/mchervenkov/inout.svg?style=flat-square)](https://packagist.org/packages/mchervenkov/inout)

[Inout JSON API Postman documentation](https://documenter.getpostman.com/view/26992907/2s93Y2S2Q8#intro)

## Installation

You can install the package via composer:

```bash
composer require mchervenkov/inout
```

If you plan to use database for storing nomenclatures:

```bash
php artisan migrate
```

If you need to export configuration file:

```bash
php artisan vendor:publish --tag=inout-config
```

If you need to export migrations:

```bash
php artisan vendor:publish --tag=inout-migrations
```

If you need to export models:

```bash
php artisan vendor:publish --tag=inout-models
```

If you need to export commands:

```bash
php artisan vendor:publish --tag=inout-commands
```

## Configuration

```bash
INOUT_ENV=test|production #default=test
INOUT_API_TOKEN= #default=test-api-token
INOUT_API_TEST_BASE_URI= #default=https://test-api.inout.bg/api/v1
INOUT_API_PRODUCTION_BASE_URI= #default=https://api1.inout.bg/api/v1
INOUT_COMPANY_ID= #default=0
INOUT_API_TIMEOUT= #default=5
INOUT_TEST_MODE= #default=1
```

## Usage

Methods
```php

// Init Inout Client
$inout = new Inout();

// Awb endpoints
$inout->awbDetails($awbNumber);
$inout->awbStatus($awbNumber);
$inout->awbPrint($awbNumber, 1);
$inout->awbHistory($awbNumber);
$inout->pickupAwbCreation(PickupAwbCreation);

// Companies endpoints
$inout->getCompanies();

// Couriers endpoints
$inout->getCouriers();
$inout->getCourierOffices($courierId);

// Locations endpoints
$inout->getCounties($countryId);
$inout->getCities(City, $countryId);
$inout->getCountries();
$inout->getCitiesSuggestions($countryId, $searchString, 1);

// Shipments endpoints
$inout->shipmentCreation(Shipment)
$inout->shipmentPrice(ShipmentPrice)

// Additional services endpoints
$inout->codInformation($referenceNumber)
$inout->phoneCallHistory($awbNumber)
$inout->waybillsHistory(WaybillsHistory)
```

Commands

```bash
#get counties with database (use -h to view options)
php artisan inout:sync-counties

#get countries with database (use -h to view options)
php artisan inout:sync-countries

#get cities with database (use -h to view options)
php artisan inout:sync-cities

#create cities map with other carriers in database  (use -h to view options)
php artisan inout:map-cities

#get company couriers with database (use -h to view options)
php artisan inout:sync-couriers

#get courier offices with database (use -h to view options)
php artisan inout:sync-courier-offices

#get inout api status (use -h to view options)
php artisan inout:api-status
```

Models
```php
InoutCity
InoutCompanyCourier
InoutCountry
InoutCounty
InoutCourierOffice
InoutApiStatus
CarrierCityMap
```

## Examples

```php
$inout = new Inout();
$shipment = new \Mchervenkov\Inout\Hydrators\Shipment([
    "senderId" => 333,
    "courierId" => 20,
    "waybillAvailableDate" => "2023-06-29",
    "serviceName" => "crossborder",
    "recipient": [
        "name" => "Test User", //Name of recipient or company. Typically, both customer names (first name and last name) or company.
        "countryIsoCode" => "HU", //Two digits country ISO code.
        "cityId" => 20144281, //The ID of a city where the shipment needs to delivery. Obtain an ID from Cities_Web_Service_v1.0. Using cityId, cityName and zipCode can be null.
        "region" => "null", //The Municipality/county were the city is located.
        "cityName" => "Balatonszárszó", //City name. If you have your own nomenclature with а cities, you can fill in the fields cityName, zipCode and region.
        "zipCode" => "8624", //Zip code.
        "streetName"  => "test street", //Name of the street, office address or office name - e.g. If you create shipment to address - Main street or Main street 10.If you want to create shipment to an office, please add a keyword "to office: " to office name or office address.
        "buildingNumber" => 0, //Number of the street/building - e.g. 10
        "officeId" => 1297, //if the courier support PUDO delivery, you can send ID of the PUDO. Obtain the PUDO ID'\''s from "Courier_Offices_Web_Service_v1.2"
        "officeCode" => "1182", //if the courier support PUDO delivery, you can send COURIER_OFFICE_CODE of the PUDO. Obtain the PUDO COURIER_OFFICE_CODE'\''s from "Courier_Offices_Web_Service_v1.2"
        "addressText" => "additional text, address test", //Additional address information – e.g. бл.25 ет 3. or bl.25 et. 3 *NOT ALL COURIERS SUPPORT IT*
        "contactPerson" => "Test User", //Contact person. Usually is the same as the name of recipient.
        "phoneNumber" => "00889000000", //Recipient'\''s phone number - mandatory for all coureirs.
        "email" => "2@2.com" // Recipient'\''s email - highly recomended
    ],
    "awb": [
        "shipmentType" => "pack", //pack or pallet
        "parcels" => 1, // Number of parcels in the shipment. *NOT ALL COURIERS SUPPORT MULTIPARCEL SERVICE*
        "envelopes" => 0, //Always send "0".
        "totalWeight" => 1.000, //Total weight of the shipment in kg. The value have to be equal to the sum of all packages.
        "declaredValue" => 0, //Insurance amount of the shipment. *NOT ALL COURIERS SUPPORT THE SERVICE*
        "bankRepayment" => 0.0, //COD amount of the shipment in local currency. You can set to 0 if the shipment doesn'\''t have a COD amount.
        "otherRepayment" => "additional text for repayment",//The service is no longer supported
        "observations" => "notes", //Additional information about the shipment/products. (notes)
        "openPackage" => false, //Check up the shipment before pay.
        "shipmentPayer" => "sender", //Always "sender"
        "saturdayDelivery" => false, //The service is no longer supported
        "referenceNumber" => "000000", //Client reference number. Important: The information in the field must be unique to avoid duplicate shipments!
        "products" => "test product", //The content of the shipment. e.g. product1 product2
        "fragile": 1, //Marker whether the content is fragile. (fragile = 1 if the content is fragile) – Used only for BG
        "productsInfo" => "1111;2222;3333;4444", //Аdditional product information.
        "piecesInPack" => 4, //Products amount in one shipment. e.g If the shipment contain 5 products the field will be filled with 5.
        "packages" => [
            "1": [
                "dimensions" => [
                    "width" => 20,
                    "height" => 20,
                    "length" => 20
                ],
                "weight" => 2.300
            ]
        ]
    ],
    "returnLabel" => [
        "nDaysValid" => 0 //By default is 0 (infinity). FUsed only for Greece
    ]
]);

$response = $inout->shipmentCreation($shipment);
dd($response);

```

### Testing
Before running tests set your api credentials in inout.php config file
```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email mario.chervenkov@gmail.com instead of using the issue tracker.

## Credits

- [Mario Chervenkov](https://github.com/mariochervenkov)
- [silabg.com](https://www.silabg.com/) :heart:
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
