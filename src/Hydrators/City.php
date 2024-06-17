<?php

namespace Mchervenkov\Inout\Hydrators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Mchervenkov\Inout\Exceptions\InoutValidationException;
use Mchervenkov\Inout\Traits\HasPagination;

class City
{
    use HasPagination;

    protected $params = [];

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
        $cityRules = [
            'county_id' => 'nullable|numeric',
            'office_id' => 'nullable|numeric'
        ];

        $pagingRules = $this->getPagingValidationRules();

        return array_merge($cityRules, $pagingRules);
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
