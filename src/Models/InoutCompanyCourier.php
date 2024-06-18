<?php

namespace Mchervenkov\Inout\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Mchervenkov\Inout\Models\InoutCompanyCourier
 *
 * @property int $id
 * @property int $courier_id
 * @property string $name
 * @property string $signature
 * @property int $to_office
 * @property int $to_address
 * @property string $country
 * @property string $currency
 * @property Collection $offices
 * @method static Builder|InoutCompanyCourier create(array $attributes)
 * @method static Builder|InoutCompanyCourier where($column, $operator = null, $value = null, $boolean = 'and')
 */

class InoutCompanyCourier extends Model
{
    use HasFactory;

    protected $table = 'inout_company_couriers';

    protected $fillable = [
        'courier_id',
        'signature',
        'name',
        'to_office',
        'to_address',
        'country',
        'currency',
    ];

    /**
     * Offices
     *
     * @return HasMany
     */
    public function offices(): HasMany
    {
        return $this->hasMany(InoutCourierOffice::class, 'courier_id', 'id');
    }
}
