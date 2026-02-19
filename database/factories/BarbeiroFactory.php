<?php declare(strict_types=1); 

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Barbeiro;
use App\Models\User;
use App\Models\Barbearia;

class BarbeiroFactory extends Factory
{
    protected $model = Barbeiro::class;

    public function definition()
    {
        return [
            'telefone' => $this->faker->phoneNumber,
            'especialidade' => $this->faker->word,
            'user_id' => User::factory()->state(['role' => 'barbeiro']),
            'barbearia_id' => Barbearia::factory(),
        ];
    }
}
