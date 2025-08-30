<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 10, 500);
        $taxAmount = $subtotal * 0.1; // 10% tax
        $total = $subtotal + $taxAmount;

        return [
            'customer_id' => Customer::factory(),
            'order_number' => 'ORD-' . strtoupper(fake()->bothify('####??##')),
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total' => $total,
            'status' => fake()->randomElement(['pending', 'paid', 'cancelled', 'refunded']),
            'items' => [
                [
                    'service_type_id' => fake()->numberBetween(1, 10),
                    'name' => fake()->randomElement(['Basic Hosting', 'Domain Registration', 'SSL Certificate']),
                    'quantity' => fake()->numberBetween(1, 3),
                    'price' => fake()->randomFloat(2, 10, 200),
                    'total' => fake()->randomFloat(2, 10, 200),
                ],
            ],
        ];
    }

    /**
     * Indicate that the order is paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
        ]);
    }

    /**
     * Indicate that the order is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }
}