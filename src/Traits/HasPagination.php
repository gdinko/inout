<?php

namespace Mchervenkov\Inout\Traits;

use Illuminate\Validation\Rule;

trait HasPagination
{
    /**
     * Validate Paging params
     *
     * @return string[]
     */
    public function getPagingValidationRules(): array
    {
        return [
            'paging' => 'required|' . Rule::in([0, 1]),
            'first' => 'required_if:paging,1',
            'skip' => 'required_if:paging,1',
        ];
    }
}
