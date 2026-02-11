<?php declare(strict_types=1); 

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Servico;
use App\Models\Barbearia;

class ServicoFactory extends Factory
{
    protected $model = Servico::class;

    public function definition()
    {
        return [
            'nome' => $this->faker->word,
            'descricao' => $this->faker->sentence,
            'duracao_minutos' => $this->faker->numberBetween(15, 120),
            'preco' => $this->faker->randomFloat(2, 20, 200),
            'imagem' => $this->faker->imageUrl(200, 200),
            'barbearia_id' => Barbearia::factory(),
        ];
    }
}
