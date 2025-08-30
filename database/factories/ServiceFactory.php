<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\ServiceType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'service_type_id' => ServiceType::factory(),
            'domain_name' => fake()->domainName(),
            'configuration' => [
                'server_location' => fake()->randomElement(['US-East', 'US-West', 'Europe', 'Asia']),
                'php_version' => fake()->randomElement(['7.4', '8.0', '8.1', '8.2']),
                'mysql_version' => '8.0',
            ],
            'status' => fake()->randomElement(['pending', 'active', 'suspended', 'cancelled']),
            'next_billing_date' => fake()->dateTimeBetween('now', '+1 year'),
            'expiry_date' => fake()->dateTimeBetween('+1 year', '+2 years'),
            'provisioning_data' => [
                'server_id' => fake()->uuid(),
                'account_username' => fake()->userName(),
                'provisioned_at' => fake()->dateTimeThisMonth(),
            ],
        ];
    }

    /**
     * Indicate that the service is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'provisioning_data' => [
                'server_id' => fake()->uuid(),
                'account_username' => fake()->userName(),
                'provisioned_at' => fake()->dateTimeThisMonth(),
            ],
        ]);
    }

    /**
     * Indicate that the service is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'provisioning_data' => null,
        ]);
    }
}