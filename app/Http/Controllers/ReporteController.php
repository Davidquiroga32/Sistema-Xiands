<?php

namespace App\Http\Controllers;

use App\Exports\ConsignacionesExport;
use App\Exports\PersonasExport;
use App\Models\Consignacion;
use App\Models\Persona;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use OwenIt\Auditing\Models\Audit;

class ReporteController extends Controller
{
    public function index(): View
    {
        $totalPersonas = Persona::count();
        $totalConsignaciones = Consignacion::count();
        $totalConsignado = Consignacion::sum('valor_consignado');
        $totalIntereses = Consignacion::sum('interes_aplicado');
        $totalConInteres = Consignacion::sum('total_con_interes');

        $consignacionesHoy = Consignacion::whereDate('fecha_consignacion', today())->count();
        $consignacionesMes = Consignacion::whereMonth('fecha_consignacion', now()->month)
            ->whereYear('fecha_consignacion', now()->year)->count();

        $auditoria = Audit::with('user')
            ->latest()
            ->take(50)
            ->get();

        return view('reportes.index', compact(
            'totalPersonas',
            'totalConsignaciones',
            'totalConsignado',
            'totalIntereses',
            'totalConInteres',
            'consignacionesHoy',
            'consignacionesMes',
            'auditoria',
        ));
    }

    public function exportPdf(Request $request)
    {
        $request->validate([
            'tipo' => ['required', 'in:consignaciones,personas'],
            'fecha_desde' => ['nullable', 'date'],
            'fecha_hasta' => ['nullable', 'date'],
        ]);

        if ($request->tipo === 'consignaciones') {
            $query = Consignacion::with('persona', 'creador')->latest('fecha_consignacion');

            if ($request->fecha_desde) {
                $query->whereDate('fecha_consignacion', '>=', $request->fecha_desde);
            }
            if ($request->fecha_hasta) {
                $query->whereDate('fecha_consignacion', '<=', $request->fecha_hasta);
            }

            $consignaciones = $query->get();
            $totalValor = $consignaciones->sum('valor_consignado');
            $totalIntereses = $consignaciones->sum('interes_aplicado');

            $pdf = Pdf::loadView('pdf.consignaciones', compact('consignaciones', 'totalValor', 'totalIntereses'));
        } else {
            $personas = Persona::with('creador')->latest()->get();

            $pdf = Pdf::loadView('pdf.personas', compact('personas'));
        }

        return $pdf->download('reporte-'.$request->tipo.'-'.now()->format('Y-m-d').'.pdf');
    }

    public function exportExcel(Request $request)
    {
        $request->validate([
            'tipo' => ['required', 'in:consignaciones,personas'],
            'fecha_desde' => ['nullable', 'date'],
            'fecha_hasta' => ['nullable', 'date'],
        ]);

        $filename = 'reporte-'.$request->tipo.'-'.now()->format('Y-m-d').'.xlsx';

        if ($request->tipo === 'consignaciones') {
            $export = new ConsignacionesExport(
                fechaDesde: $request->fecha_desde,
                fechaHasta: $request->fecha_hasta,
            );

            return Excel::download($export, $filename);
        }

        return Excel::download(new PersonasExport, $filename);
    }
}
