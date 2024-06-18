<?php

namespace Mchervenkov\Inout\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Mchervenkov\Inout\Models\InoutCounty
 *
 * @property int $id
 * @property int $county_id
 * @property string $abr
 * @property string $name
 * @method static Builder|InoutCompanyCourier create(array $attributes)
 * @method static Builder|InoutCompanyCourier where($column, $operator = null, $value = null, $boolean = 'and')
 */

class InoutCounty extends Model
{
    use HasFactory;

    protected $table = 'inout_counties';

    protected $fillable = [
        'county_id',
        'abr',
        'name'
    ];
}
