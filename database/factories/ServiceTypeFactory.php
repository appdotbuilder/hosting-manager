<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceType>
 */
class ServiceTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->randomElement([
            'Basic Hosting',
            'Professional Hosting',
            'Enterprise Hosting',
            'Domain Registration',
            'SSL Certificate',
            'Email Hosting',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),
            'type' => fake()->randomElement(['hosting', 'domain', 'ssl', 'email']),
            'price' => fake()->randomFloat(2, 5, 200),
            'billing_cycle' => fake()->randomElement(['monthly', 'quarterly', 'yearly']),
            'features' => [
                'storage' => fake()->randomElement(['1GB', '5GB', '10GB', 'Unlimited']),
                'bandwidth' => fake()->randomElement(['10GB', '50GB', '100GB', 'Unlimited']),
                'email_accounts' => fake()->numberBetween(1, 100),
                'databases' => fake()->numberBetween(1, 10),
            ],
            'active' => fake()->boolean(90),
        ];
    }

    /**
     * Indicate that the service type is for hosting.
     */
    public function hosting(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'hosting',
            'features' => [
                'storage' => fake()->randomElement(['1GB', '5GB', '10GB', 'Unlimited']),
                'bandwidth' => fake()->randomElement(['10GB', '50GB', '100GB', 'Unlimited']),
                'email_accounts' => fake()->numberBetween(1, 100),
                'databases' => fake()->numberBetween(1, 10),
                'ssl_included' => fake()->boolean(70),
            ],
        ]);
    }

    /**
     * Indicate that the service type is for domains.
     */
    public function domain(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'domain',
            'billing_cycle' => 'yearly',
            'features' => [
                'dns_management' => true,
                'domain_forwarding' => true,
                'privacy_protection' => fake()->boolean(80),
            ],
        ]);
    }
}