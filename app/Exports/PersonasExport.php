<?php

namespace App\Exports;

use App\Models\Persona;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class PersonasExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping, WithTitle
{
    public function query()
    {
        return Persona::with('creador')->latest();
    }

    public function headings(): array
    {
        return [
            'Nombre Completo',
            'Cédula',
            'Correo Electrónico',
            'Teléfono',
            'Dirección',
            'Codeudor',
            'Registrado por',
            'Fecha Registro',
        ];
    }

    public function map($persona): array
    {
        return [
            $persona->nombre_completo,
            $persona->cedula,
            $persona->correo_electronico,
            $persona->numero_telefono,
            $persona->direccion,
            $persona->nombre_codeudor,
            $persona->creador?->name ?? '—',
            $persona->created_at?->format('Y-m-d H:i'),
        ];
    }

    public function title(): string
    {
        return 'Personas';
    }
}
