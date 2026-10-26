<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'is_member' => false,
            'member_discount' => 0,
            'member_discount_type' => 'percent',
            'member_discount_quota' => 0,
            'member_discount_remaining' => 0,
        ];
    }

    /**
     * Indicate that the customer is a VIP member.
     */
    public function member(float $discount = 10, int $quota = 5): static
    {
        return $this->state(fn (array $attributes) => [
            'is_member' => true,
            'member_discount' => $discount,
            'member_discount_type' => 'percent',
            'member_discount_quota' => $quota,
            'member_discount_remaining' => $quota,
        ]);
    }
}
