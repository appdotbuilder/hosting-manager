<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Service
 *
 * @property int $id
 * @property int $customer_id
 * @property int $service_type_id
 * @property string|null $domain_name
 * @property array|null $configuration
 * @property string $status
 * @property string|null $next_billing_date
 * @property string|null $expiry_date
 * @property array|null $provisioning_data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer $customer
 * @property-read \App\Models\ServiceType $serviceType
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Service newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Service newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Service query()
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereConfiguration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereDomainName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereNextBillingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereProvisioningData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereServiceTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereUpdatedAt($value)
 * @method static \Database\Factories\ServiceFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Service extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'customer_id',
        'service_type_id',
        'domain_name',
        'configuration',
        'status',
        'next_billing_date',
        'expiry_date',
        'provisioning_data',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'configuration' => 'array',
        'next_billing_date' => 'date',
        'expiry_date' => 'date',
        'provisioning_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the customer that owns the service.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the service type that defines the service.
     */
    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }
}