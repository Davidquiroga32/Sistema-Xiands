<?php

namespace Database\Factories;

use App\Models\Consignacion;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Consignacion>
 */
class ConsignacionFactory extends Factory
{
    protected $model = Consignacion::class;

    public function definition(): array
    {
        return [
            'persona_id' => Persona::factory(),
            'valor_consignado' => fake()->numberBetween(50000, 5000000),
            'fecha_consignacion' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'observacion' => null,
            'estado' => 'vigente',
            'created_by' => User::factory(),
        ];
    }
}
