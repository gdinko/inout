<?php

namespace Mchervenkov\Inout\Hydrators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Mchervenkov\Inout\Exceptions\InoutValidationException;

class Shipment
{
    /**
     * Request Params
     *
     * @var array
     */
    private $params;

    /**
     * __construct
     *
     * @param  array $params
     * @return void
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * Validation Rules
     *
     * @return array
     */
    protected function validationRules(): array
    {
        return [
            'senderId' => 'required|numeric',
            'courierId' => 'required|numeric',
            'waybillAvailableDate' => 'required|date',
            'serviceName' => 'required|string',
            'recipient' => 'required|array',
            'recipient.name' => 'required|string',
            'recipient.cityId' => 'required|numeric',
            'recipient.countryIsoCode' => 'nullable|string',
            'recipient.cityName' => 'nullable|string',
            'recipient.zipCode' => 'nullable|string',
            'recipient.region' => 'nullable|string',
            'recipient.streetName' => 'required|string',
            'recipient.buildingNumber' => 'required|numeric',
            'recipient.addressText' => 'required|string',
            'recipient.contactPerson' => 'nullable|string',
            'recipient.phoneNumber' => 'required|string',
            'recipient.email' => 'nullable|string',
            'awb' => 'required|array',
            'awb.shipmentType' => 'nullable|string',
            'awb.parcels' => 'required|numeric',
            'awb.envelopes' => 'required|numeric',
            'awb.pallets' => 'nullable|numeric',
            'awb.totalWeight' => 'required|numeric',
            'awb.declaredValue' => 'nullable|numeric',
            'awb.bankRepayment' => 'nullable|numeric',
            'awb.otherRepayment' => 'nullable|string',
            'awb.observations' => 'nullable|string',
            'awb.openPackage' => 'nullable|boolean',
            'awb.saturdayDelivery' => 'nullable|boolean',
            'referenceNumber' => 'required|string',
            'products' => 'required|string',
            'fragile' => 'nullable|boolean',
            'productsInfo' => 'nullable|string',
            'piecesInPack' => 'nullable|numeric',
            'document' => 'nullable|array',
            'document.content' => 'nullable|string',
            'document.format' => 'nullable|string',
            'packages' => 'nullable|array',
            'packages.*.dimensions' => 'nullable|array',
            'packages.*.dimensions.width' => 'nullable|numeric',
            'packages.*.dimensions.height' => 'nullable|numeric',
            'packages.*.dimensions.length' => 'nullable|numeric',
            'packages.*.dimensions.weight' => 'nullable|numeric',
            'customsData' => 'nullable|array',
            'customsData.dutyPaymentInfo' => 'nullable|string|'. Rule::in(['DDU', 'DDP']),
            'customsData.customsValue' => 'nullable|string',
            'courierRequest' => 'nullable|array',
            'courierRequest.date' => 'nullable|date',
            'courierRequest.timeFrom' => 'nullable|string',
            'courierRequest.timeTo' => 'nullable|string',
            'returnLabel' => 'nullable|array',
            'returnLabel.ndaysValid' => 'nullable|numeric',
        ];
    }

    /**
     * Validated Data
     *
     * @return array
     * @throws InoutValidationException
     * @throws ValidationException
     */
    public function validated(): array
    {
        $validator = Validator::make(
            $this->params,
            $this->validationRules()
        );

        if ($validator->fails()) {
            throw new InoutValidationException(
                __CLASS__,
                422,
                $validator->messages()->toArray()
            );
        }

        return $validator->validated();
    }
}
