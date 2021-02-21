<?php

namespace Database\Factories;

use App\Models\Day;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DayFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = Day::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'user_id' => User::factory(),
      'day' => $this->faker->dateTimeThisYear(),
    ];
  }
}
