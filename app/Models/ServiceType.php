<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\ServiceType
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string $type
 * @property float $price
 * @property string $billing_cycle
 * @property array|null $features
 * @property bool $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Service> $services
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceType query()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceType whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceType whereBillingCycle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceType whereFeatures($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceType wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceType whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceType whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceType whereUpdatedAt($value)
 * @method static \Database\Factories\ServiceTypeFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class ServiceType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'price',
        'billing_cycle',
        'features',
        'active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'features' => 'array',
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the services for the service type.
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }
}