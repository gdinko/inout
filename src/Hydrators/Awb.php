<?php

namespace Mchervenkov\Inout\Hydrators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Mchervenkov\Inout\Exceptions\InoutValidationException;

class Awb
{
    /**
     * Awb request data
     *
     * @var array
     */
    private array $data;

    /**
     * __construct
     *
     * @param  array $data
     * @return void
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * validated
     *
     * @return array
     * @throws InoutValidationException
     * @throws ValidationException
     */
    public function validated(): array
    {
        $validator = Validator::make(
            $this->data,
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

    /**
     * validationRules
     *
     * @return array
     */
    protected function validationRules(): array
    {
        return [
            'number' => 'nullable|string',
            'startTimestamp' => 'nullable|date',
            'endTimestamp' => 'nullable|date',
        ];
    }
}
