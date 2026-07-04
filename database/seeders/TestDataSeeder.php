<?php

namespace Database\Seeders;

use App\Models\Consignacion;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $secretaria = User::firstOrCreate(
            ['email' => 'secretaria@xiands.com'],
            [
                'name' => 'Secretaria',
                'password' => bcrypt('password'),
            ]
        );
        $secretaria->assignRole('secretaria');

        $admin = User::where('email', 'admin@xiands.com')->first();

        $personas = [
            [
                'nombre_completo' => 'María García López',
                'cedula' => '1002003001',
                'correo_electronico' => 'maria.garcia@email.com',
                'numero_telefono' => '3001112233',
                'direccion' => 'Calle 45 #12-34, Bogotá',
                'nombre_codeudor' => 'Carlos García',
            ],
            [
                'nombre_completo' => 'Juan Martínez Ruiz',
                'cedula' => '1002003002',
                'correo_electronico' => 'juan.martinez@email.com',
                'numero_telefono' => '3102223344',
                'direccion' => 'Carrera 7 #23-45, Medellín',
                'nombre_codeudor' => null,
            ],
            [
                'nombre_completo' => 'Ana Rodríguez Torres',
                'cedula' => '1002003003',
                'correo_electronico' => null,
                'numero_telefono' => '3203334455',
                'direccion' => 'Avenida Siempre Viva #742, Cali',
                'nombre_codeudor' => 'Pedro Rodríguez',
            ],
            [
                'nombre_completo' => 'Luis Fernando Díaz',
                'cedula' => '1002003004',
                'correo_electronico' => 'luis.diaz@email.com',
                'numero_telefono' => '3154445566',
                'direccion' => 'Diagonal 23 #56-78, Barranquilla',
                'nombre_codeudor' => null,
            ],
            [
                'nombre_completo' => 'Carmen Herrera Sánchez',
                'cedula' => '1002003005',
                'correo_electronico' => 'carmen.herrera@email.com',
                'numero_telefono' => null,
                'direccion' => 'Calle 80 #15-22, Bogotá',
                'nombre_codeudor' => 'José Herrera',
            ],
            [
                'nombre_completo' => 'Andrés Felipe Morales',
                'cedula' => '1002003006',
                'correo_electronico' => 'andres.morales@email.com',
                'numero_telefono' => '3185556677',
                'direccion' => 'Transversal 5 #45-12, Cartagena',
                'nombre_codeudor' => 'Diana Morales',
            ],
            [
                'nombre_completo' => 'Sofía Ramírez Ortiz',
                'cedula' => '1002003007',
                'correo_electronico' => 'sofia.ramirez@email.com',
                'numero_telefono' => '3216667788',
                'direccion' => 'Carrera 15 #89-45, Bucaramanga',
                'nombre_codeudor' => null,
            ],
            [
                'nombre_completo' => 'Diego Alejandro Castro',
                'cedula' => '1002003008',
                'correo_electronico' => null,
                'numero_telefono' => '3007778899',
                'direccion' => 'Calle 12 #34-56, Pereira',
                'nombre_codeudor' => 'Laura Castro',
            ],
        ];

        if (Persona::count() > 0) {
            return;
        }

        foreach ($personas as $data) {
            $data['created_by'] = $admin->id;
            Persona::create($data);
        }

        $todasPersonas = Persona::all();

        $consignaciones = [
            ['persona' => 0, 'valor' => 500000, 'fecha' => '2026-06-18', 'obs' => 'Abono inicial', 'interes' => true, 'interes_by' => 'admin'],
            ['persona' => 0, 'valor' => 350000, 'fecha' => '2026-06-15', 'obs' => 'Segundo abono', 'interes' => false, 'interes_by' => null],
            ['persona' => 0, 'valor' => 800000, 'fecha' => '2026-06-10', 'obs' => null, 'interes' => true, 'interes_by' => 'admin'],
            ['persona' => 1, 'valor' => 1200000, 'fecha' => '2026-06-17', 'obs' => 'Consignación inicial', 'interes' => false, 'interes_by' => null],
            ['persona' => 1, 'valor' => 250000, 'fecha' => '2026-06-12', 'obs' => 'Abono parcial', 'interes' => false, 'interes_by' => null],
            ['persona' => 2, 'valor' => 1500000, 'fecha' => '2026-06-19', 'obs' => 'Pago completo', 'interes' => false, 'interes_by' => null],
            ['persona' => 2, 'valor' => 600000, 'fecha' => '2026-06-14', 'obs' => null, 'interes' => true, 'interes_by' => 'admin'],
            ['persona' => 3, 'valor' => 900000, 'fecha' => '2026-06-16', 'obs' => 'Primera consignación', 'interes' => false, 'interes_by' => null],
            ['persona' => 3, 'valor' => 450000, 'fecha' => '2026-06-08', 'obs' => 'Abono', 'interes' => true, 'interes_by' => 'admin'],
            ['persona' => 3, 'valor' => 300000, 'fecha' => '2026-06-01', 'obs' => null, 'interes' => false, 'interes_by' => null],
            ['persona' => 4, 'valor' => 700000, 'fecha' => '2026-06-18', 'obs' => 'Consignación', 'interes' => false, 'interes_by' => null],
            ['persona' => 4, 'valor' => 1100000, 'fecha' => '2026-06-09', 'obs' => 'Abono grande', 'interes' => true, 'interes_by' => 'admin'],
            ['persona' => 5, 'valor' => 400000, 'fecha' => '2026-06-19', 'obs' => 'Consignación del día', 'interes' => false, 'interes_by' => null],
            ['persona' => 6, 'valor' => 2000000, 'fecha' => '2026-06-17', 'obs' => 'Pago total', 'interes' => false, 'interes_by' => null],
            ['persona' => 6, 'valor' => 550000, 'fecha' => '2026-06-13', 'obs' => null, 'interes' => true, 'interes_by' => 'admin'],
            ['persona' => 6, 'valor' => 350000, 'fecha' => '2026-06-05', 'obs' => 'Abono', 'interes' => false, 'interes_by' => null],
            ['persona' => 7, 'valor' => 1800000, 'fecha' => '2026-06-16', 'obs' => 'Consignación grande', 'interes' => false, 'interes_by' => null],
            ['persona' => 7, 'valor' => 950000, 'fecha' => '2026-06-11', 'obs' => 'Segundo pago', 'interes' => true, 'interes_by' => 'admin'],
            ['persona' => 7, 'valor' => 400000, 'fecha' => '2026-06-03', 'obs' => null, 'interes' => false, 'interes_by' => null],
            ['persona' => 0, 'valor' => 200000, 'fecha' => '2026-06-19', 'obs' => 'Abono extra hoy', 'interes' => false, 'interes_by' => null],
        ];

        foreach ($consignaciones as $data) {
            $persona = $todasPersonas[$data['persona']];
            $valor = $data['valor'];
            $tieneInteres = $data['interes'];

            $consig = new Consignacion([
                'persona_id' => $persona->id,
                'valor_consignado' => $valor,
                'fecha_consignacion' => $data['fecha'],
                'observacion' => $data['obs'],
                'created_by' => $admin->id,
            ]);

            if ($tieneInteres) {
                $interes = round($valor * 0.05, 2);
                $consig->interes_aplicado = $interes;
                $consig->total_con_interes = round($valor + $interes, 2);
                $consig->interes_aplicado_by = $admin->id;
                $consig->interes_aplicado_at = now();
            }

            $consig->save();
        }
    }
}
