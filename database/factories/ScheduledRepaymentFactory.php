<?php

namespace Database\Factories;

use App\Models\ScheduledRepayment;
use App\Models\Loan;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduledRepaymentFactory extends Factory
{
    protected $model = ScheduledRepayment::class;

    public function definition()
    {
        return [
            'loan_id' => Loan::factory(),
            'amount' => $this->faker->randomFloat(2, 100, 1000),
            'due_date' => $this->faker->dateTimeBetween('+1 month', '+6 months'),
        ];
    }
}
