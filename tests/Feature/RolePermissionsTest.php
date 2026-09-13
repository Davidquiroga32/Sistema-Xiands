<?php

namespace Tests\Feature;

use App\Models\Persona;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RolePermissionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'administradora', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'secretaria', 'guard_name' => 'web']);
    }

    public function test_secretaria_puede_ver_reportes(): void
    {
        $secretaria = User::factory()->create();
        $secretaria->assignRole('secretaria');

        $response = $this->actingAs($secretaria)->get(route('reportes.index'));

        $response->assertOk();
    }

    public function test_secretaria_puede_acceder_a_gestion_de_interes(): void
    {
        $secretaria = User::factory()->create();
        $secretaria->assignRole('secretaria');

        $persona = Persona::factory()->create();

        $response = $this->actingAs($secretaria)->post(route('personas.interes-batch', $persona), [
            'tasa' => 5,
        ]);

        $response->assertRedirect();
    }

    public function test_secretaria_no_puede_gestionar_usuarios(): void
    {
        $secretaria = User::factory()->create();
        $secretaria->assignRole('secretaria');

        $response = $this->actingAs($secretaria)->get(route('users.index'));

        $response->assertForbidden();
    }

    public function test_secretaria_no_puede_cambiar_su_contrasena(): void
    {
        $secretaria = User::factory()->create();
        $secretaria->assignRole('secretaria');

        $response = $this->actingAs($secretaria)->put(route('password.update'), [
            'current_password' => 'password',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

        $response->assertForbidden();
    }

    public function test_administradora_puede_gestionar_usuarios(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('administradora');

        $response = $this->actingAs($admin)->get(route('users.index'));

        $response->assertOk();
    }
}
