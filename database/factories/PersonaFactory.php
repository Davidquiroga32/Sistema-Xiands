<?php

namespace Database\Factories;

use App\Models\Persona;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Persona>
 */
class PersonaFactory extends Factory
{
    protected $model = Persona::class;

    public function definition(): array
    {
        return [
            'nombre_completo' => fake()->name(),
            'cedula' => fake()->unique()->numerify('##########'),
            'correo_electronico' => fake()->unique()->safeEmail(),
            'numero_telefono' => fake()->numerify('3########'),
            'direccion' => fake()->address(),
            'nombre_codeudor' => null,
            'tasa_interes' => 5.00,
            'created_by' => User::factory(),
        ];
    }
}
