<?php

namespace Mchervenkov\Inout\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Mchervenkov\Inout\Models\InoutCompanyCourier
 *
 * @property int $id
 * @property int $courier_id
 * @property string $name
 * @property int $to_office
 * @property int $to_address
 * @property string $country
 * @property string $currency
 * @method static Builder|InoutCompanyCourier create(array $attributes)
 * @method static Builder|InoutCompanyCourier where($column, $operator = null, $value = null, $boolean = 'and')
 */

class InoutCompanyCourier extends Model
{
    use HasFactory;

    protected $table = 'inout_company_couriers';

    protected $fillable = [
        'courier_id',
        'name',
        'to_office',
        'to_address',
        'country',
        'currency',
    ];
}
