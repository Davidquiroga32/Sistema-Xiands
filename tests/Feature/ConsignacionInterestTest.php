<?php

namespace Tests\Feature;

use App\Models\Consignacion;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ConsignacionInterestTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'administradora', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'secretaria', 'guard_name' => 'web']);
    }

    public function test_crear_consignacion_aplica_tasa_interes_de_la_persona(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('administradora');

        $persona = Persona::factory()->create(['tasa_interes' => 8.00]);

        $response = $this->actingAs($admin)->post(route('consignaciones.store'), [
            'persona_id' => $persona->id,
            'valor_consignado' => 100000,
            'fecha_consignacion' => now()->toDateString(),
        ]);

        $consignacion = Consignacion::first();

        $this->assertNotNull($consignacion);
        $this->assertEquals(8.00, (float) $consignacion->tasa_aplicada);
        $this->assertEquals(8000.00, (float) $consignacion->interes_aplicado);
        $this->assertEquals(108000.00, (float) $consignacion->total_con_interes);

        $response->assertRedirect();
    }

    public function test_aplicar_interes_usa_tasa_de_la_persona(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('administradora');

        $persona = Persona::factory()->create(['tasa_interes' => 10.00]);
        $consignacion = Consignacion::factory()->create([
            'persona_id' => $persona->id,
            'valor_consignado' => 200000,
            'tasa_aplicada' => 0,
            'interes_aplicado' => 0,
            'total_con_interes' => 0,
        ]);

        $this->actingAs($admin)->post(route('consignaciones.interes', $consignacion));

        $consignacion->refresh();

        $this->assertEquals(10.00, (float) $consignacion->tasa_aplicada);
        $this->assertEquals(20000.00, (float) $consignacion->interes_aplicado);
        $this->assertEquals(220000.00, (float) $consignacion->total_con_interes);
    }

    public function test_no_se_puede_eliminar_el_ultimo_administrador(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('administradora');

        $response = $this->actingAs($admin)->delete(route('profile.destroy'), [
            'password' => 'password',
        ]);

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
        $response->assertSessionHasErrors('password', null, 'userDeletion');
    }
}
