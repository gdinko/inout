<?php

namespace Mchervenkov\Inout\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Mchervenkov\Inout\Models\InoutCountry
 *
 * @property int $id
 * @property int $country_id
 * @property string $name
 * @property string $cyrillic_name
 * @property string $iso_code
 * @method static Builder|InoutCompanyCourier create(array $attributes)
 * @method static Builder|InoutCompanyCourier where($column, $operator = null, $value = null, $boolean = 'and')
 */

class InoutCountry extends Model
{
    use HasFactory;

    protected $table = 'inout_countries';

    protected $fillable = [
        'courier_id',
        'name',
        'cyrillic_name',
        'iso_code',
    ];
}
