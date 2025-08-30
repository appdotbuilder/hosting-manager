<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $amount = fake()->randomFloat(2, 10, 500);
        $taxAmount = $amount * 0.1; // 10% tax
        $total = $amount + $taxAmount;

        return [
            'customer_id' => Customer::factory(),
            'order_id' => fake()->boolean(70) ? Order::factory() : null,
            'invoice_number' => 'INV-' . strtoupper(fake()->bothify('####??##')),
            'amount' => $amount,
            'tax_amount' => $taxAmount,
            'total' => $total,
            'status' => fake()->randomElement(['pending', 'paid', 'overdue', 'cancelled']),
            'due_date' => fake()->dateTimeBetween('now', '+30 days'),
            'paid_at' => fake()->boolean(60) ? fake()->dateTimeThisMonth() : null,
            'line_items' => [
                [
                    'description' => fake()->randomElement(['Hosting Service', 'Domain Registration', 'SSL Certificate']),
                    'quantity' => 1,
                    'price' => $amount,
                    'total' => $amount,
                ],
            ],
            'is_recurring' => fake()->boolean(40),
        ];
    }

    /**
     * Indicate that the invoice is paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
            'paid_at' => fake()->dateTimeThisMonth(),
        ]);
    }

    /**
     * Indicate that the invoice is overdue.
     */
    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'overdue',
            'due_date' => fake()->dateTimeBetween('-30 days', '-1 day'),
            'paid_at' => null,
        ]);
    }

    /**
     * Indicate that the invoice is recurring.
     */
    public function recurring(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_recurring' => true,
        ]);
    }
}