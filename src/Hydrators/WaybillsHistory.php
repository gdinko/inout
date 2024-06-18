<?php

namespace Mchervenkov\Inout\Hydrators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Mchervenkov\Inout\Exceptions\InoutValidationException;

class WaybillsHistory
{
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
            'awbs' => 'required|array',
            'awbs.*.awb' => 'required|string'
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
