<?php

namespace App\Http\Controllers;

use App\Models\Consignacion;
use App\Models\Persona;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalPersonas = Persona::count();
        $totalConsignaciones = Consignacion::vigente()->count();
        $totalConsignado = Consignacion::vigente()->sum('valor_consignado');
        $totalIntereses = Consignacion::vigente()->sum('interes_aplicado');

        $hoy = Consignacion::vigente()->whereDate('fecha_consignacion', today())->count();
        $mesActual = Consignacion::vigente()->whereMonth('fecha_consignacion', now()->month)
            ->whereYear('fecha_consignacion', now()->year)->count();
        $mesAnterior = Consignacion::vigente()->whereMonth('fecha_consignacion', now()->subMonth()->month)
            ->whereYear('fecha_consignacion', now()->subMonth()->year)->count();

        $ultimasConsignaciones = Consignacion::with('persona')
            ->latest()->take(5)->get();

        return view('dashboard.index', compact(
            'totalPersonas', 'totalConsignaciones', 'totalConsignado',
            'totalIntereses', 'hoy', 'mesActual', 'mesAnterior',
            'ultimasConsignaciones'
        ));
    }
}
