<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Consignacion;
use App\Models\Persona;

class CleanDatabase extends Command
{
    protected $signature = 'db:clean';

    protected $description = 'Limpia consignaciones, personas y auditoría. Conserva usuarios.';

    public function handle()
    {
        $this->warn('Limpiando base de datos...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        Consignacion::truncate();
        $this->info('  Consignaciones eliminadas.');

        Persona::truncate();
        $this->info('  Personas eliminadas.');

        DB::table('audits')->truncate();
        $this->info('  Auditoría eliminada.');

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->newLine();
        $this->info('Base limpia. Usuarios intactos.');
    }
}
