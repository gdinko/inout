<?php

namespace Mchervenkov\Inout\Hydrators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Mchervenkov\Inout\Exceptions\InoutValidationException;

class ShipmentPrice
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
            'courierId' => 'required|numeric',
            'companyId' => 'required|numeric',
            'weight' => 'required|numeric',
            'codAmount' => 'required|numeric',
            'openPackage' => 'required|numeric',
            'returnDocs' => 'required|numeric|' . Rule::in([0, 1, 2]),
            'saturdayDelivery' => 'required|numeric',
            'city' => 'nullable|string',
            'county' => 'nullable|string',
            'toOffice' => 'nullable|numeric',
            'currency' => 'nullable|string',
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
