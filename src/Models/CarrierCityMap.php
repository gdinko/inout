<?php

namespace Mchervenkov\Inout\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Mchervenkov\Inout\Models\CarrierCityMap
 *
 * @property int $id
 * @property string $carrier_signature
 * @property int $carrier_city_id
 * @property string $country_code
 * @property string|null $region
 * @property string $name
 * @property string $name_slug
 * @property string $post_code
 * @property string $slug
 * @property string $uuid
 * @method static Builder|CarrierCityMap create(array $attributes)
 * @method static Builder|CarrierCityMap where($column, $operator = null, $value = null, $boolean = 'and')
 */
class CarrierCityMap extends Model
{
    use HasFactory;

    protected $table = 'carrier_city_map';

    protected $fillable = [
        'carrier_signature',
        'carrier_city_id',
        'country_code',
        'region',
        'name',
        'name_slug',
        'post_code',
        'slug',
        'uuid',
    ];
}
