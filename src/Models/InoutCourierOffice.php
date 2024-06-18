<?php

namespace Mchervenkov\Inout\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Mchervenkov\Inout\Models\InoutCourierOffice
 *
 * @property int $id
 * @property int $office_id
 * @property int $city_id
 * @property int $courier_id
 * @property int $courier_office_id
 * @property int $group_city_id
 * @property string $office_name
 * @property string $courier_office_code
 * @property string $city_name
 * @property string $address
 * @property string $latitude
 * @property string $longitude
 * @property string $work_end
 * @property string $work_begin
 * @property string $work_begin_saturday
 * @property string $work_end_saturday
 * @property string $post_code
 * @property string $region
 * @property string $city_uuid
 * @property InoutCity $city
 * @method static Builder|InoutCompanyCourier create(array $attributes)
 * @method static Builder|InoutCompanyCourier where($column, $operator = null, $value = null, $boolean = 'and')
 */
class  InoutCourierOffice extends Model
{
    use HasFactory;

    protected $table = 'inout_courier_offices';

    protected $fillable = [
        'office_id',
        'city_id',
        'courier_id',
        'courier_office_id',
        'group_city_id',
        'office_name',
        'courier_office_code',
        'city_name',
        'address',
        'latitude',
        'longitude',
        'work_end',
        'work_begin',
        'work_begin_saturday',
        'work_end_saturday',
        'post_code',
        'region',
        'city_uuid',
    ];

    /**
     * Inout City
     *
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(InoutCity::class, 'city_id', 'city_id');
    }
}
