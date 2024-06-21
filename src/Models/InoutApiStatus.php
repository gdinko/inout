<?php

namespace Mchervenkov\Inout\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Mchervenkov\Inout\Models\InoutApiStatus
 *
 * @property int $id
 * @property int $code
 * @method static Builder|CarrierCityMap create(array $attributes)
 * @method static Builder|CarrierCityMap where($column, $operator = null, $value = null, $boolean = 'and')
 */
class InoutApiStatus extends Model
{
    protected $table = 'inout_api_status';

    protected $fillable = [
        'code',
    ];
}
