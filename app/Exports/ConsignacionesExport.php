<?php

namespace App\Exports;

use App\Models\Consignacion;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class ConsignacionesExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping, WithTitle
{
    public function __construct(
        private readonly ?string $fechaDesde = null,
        private readonly ?string $fechaHasta = null,
    ) {}

    public function query()
    {
        $query = Consignacion::with('persona');

        if ($this->fechaDesde) {
            $query->whereDate('fecha_consignacion', '>=', $this->fechaDesde);
        }

        if ($this->fechaHasta) {
            $query->whereDate('fecha_consignacion', '<=', $this->fechaHasta);
        }

        return $query->latest('fecha_consignacion');
    }

    public function headings(): array
    {
        return [
            'Persona',
            'Cédula',
            'Valor Consignado',
            'Fecha Consignación',
            'Interés Aplicado',
            'Total con Interés',
            'Observación',
            'Tipo Comprobante',
            'Registrado por',
        ];
    }

    public function map($consignacion): array
    {
        return [
            $consignacion->persona?->nombre_completo ?? '—',
            $consignacion->persona?->cedula ?? '—',
            $consignacion->valor_consignado,
            $consignacion->fecha_consignacion?->format('Y-m-d'),
            $consignacion->interes_aplicado,
            $consignacion->total_con_interes ?: $consignacion->valor_consignado,
            $consignacion->observacion,
            $consignacion->comprobante_tipo ?? 'Efectivo',
            $consignacion->creador?->name ?? '—',
        ];
    }

    public function title(): string
    {
        return 'Consignaciones';
    }
}
