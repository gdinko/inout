<?php

namespace Mchervenkov\Inout\Hydrators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Mchervenkov\Inout\Exceptions\InoutValidationException;

class PickupAwbCreation
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
            'companyId' => 'required|numeric',
            'courierId' => 'nullable|numeric',
            'sender' => 'required|array',
            'sender.name' => 'required|string',
            'sender.cityId' => 'required|numeric',
            'sender.countryIsoCode' => 'required|string',
            'sender.cityName' => 'nullable|string',
            'sender.zipCode' => 'nullable|string',
            'sender.region' => 'nullable|numeric',
            'sender.streetName' => 'required|string',
            'sender.contactPerson' => 'nullable|string',
            'sender.phoneNumber' => 'required|string',
            'sender.email' => 'required|string',
            'awb' => 'required|array',
            'awb.parcels' => 'nullable|numeric',
            'awb.totalWeight' => 'required|numeric',
            'awb.observations' => 'required|string',
            'awb.email' => 'nullable|string',
            'awb.referenceNumber' => 'required|string',
            'awb.products' => 'required|string',
            'awb.packages' => 'required|array',
            'awb.packages.*.weight' => 'required|numeric',
            'awb.packages.*.dimensions' => 'required|array',
            'awb.packages.*.dimensions.width' => 'required|numeric',
            'awb.packages.*.dimensions.height' => 'required|numeric',
            'awb.packages.*.dimensions.length' => 'required|numeric',
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
