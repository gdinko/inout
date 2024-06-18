<?php

namespace Mchervenkov\Inout\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Mchervenkov\Inout\Models\InoutCity
 *
 * @property int $id
 * @property int $city_id
 * @property int $county_id
 * @property string $postal_code
 * @property string $name_local
 * @property string $name_en
 * @property string $county_name
 * @property string $county_name_en
 * @property string $municipality
 * @property string $state_name
 * @property string $state_name_en
 * @property int $manual_edit
 * @property Collection $offices
 * @method static Builder|InoutCompanyCourier create(array $attributes)
 * @method static Builder|InoutCompanyCourier where($column, $operator = null, $value = null, $boolean = 'and')
 */
class InoutCity extends Model
{
    use HasFactory;

    protected $table = 'inout_cities';

    protected $fillable = [
        'city_id',
        'county_id',
        'postal_code',
        'name_local',
        'name_en',
        'county_name',
        'county_name_en',
        'municipality',
        'state_name',
        'state_name_en',
        'manual_edit',
    ];

    /**
     * Offices
     *
     * @return HasMany
     */
    public function offices(): HasMany
    {
        return $this->hasMany(InoutCourierOffice::class);
    }
}
