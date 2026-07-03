<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdatePersonaRequest;
use App\Models\Persona;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PersonaController extends Controller
{
    public function index(): View
    {
        $personas = Persona::latest()->paginate(15);

        return view('personas.index', compact('personas'));
    }

    public function create(): View
    {
        return view('personas.create');
    }

    public function store(StorePersonaRequest $request): RedirectResponse
    {
        $persona = Persona::create([
            ...$request->validated(),
            'created_by' => auth()->id(),
        ]);

        return redirect()
            ->route('personas.show', $persona)
            ->with('success', 'Persona creada exitosamente.');
    }

    public function show(Persona $persona): View
    {
        $totalConsignado = $persona->consignaciones()->sum('valor_consignado');
        $totalIntereses = $persona->consignaciones()->sum('interes_aplicado');
        $totalConInteres = $persona->consignaciones()->sum('total_con_interes');
        $numeroConsignaciones = $persona->consignaciones()->count();
        $ultimaConsignacion = $persona->consignaciones()->latest('fecha_consignacion')->first();
        $consignaciones = $persona->consignaciones()->latest()->paginate(10);

        return view('personas.show', compact(
            'persona',
            'totalConsignado',
            'totalIntereses',
            'totalConInteres',
            'numeroConsignaciones',
            'ultimaConsignacion',
            'consignaciones'
        ));
    }

    public function edit(Persona $persona): View
    {
        return view('personas.edit', compact('persona'));
    }

    public function update(UpdatePersonaRequest $request, Persona $persona): RedirectResponse
    {
        $persona->update($request->validated());

        return redirect()->back()->with('success', 'Persona actualizada exitosamente.');
    }

    public function destroy(Persona $persona): RedirectResponse
    {
        $this->authorize('delete', $persona);

        $persona->delete();

        return redirect()
            ->route('personas.index')
            ->with('success', 'Persona eliminada exitosamente.');
    }

    public function deactivate(Persona $persona): RedirectResponse
    {
        $this->authorize('delete', $persona);

        $persona->delete();

        return redirect()->back()->with('success', 'Persona desactivada exitosamente.');
    }

    public function restore(Persona $persona): RedirectResponse
    {
        $this->authorize('restore', $persona);

        $persona->restore();

        return redirect()->back()->with('success', 'Persona reactivada exitosamente.');
    }

    public function aplicarInteresBatch(Request $request, Persona $persona): RedirectResponse
    {
        $rate = (float) $request->input('tasa', 5);
        $fechaDesde = $request->input('fecha_desde');
        $fechaHasta = $request->input('fecha_hasta');

        $persona->update(['tasa_interes' => $rate]);

        $query = $persona->consignaciones();

        if ($fechaDesde) {
            $query->where('fecha_consignacion', '>=', $fechaDesde);
        }
        if ($fechaHasta) {
            $query->where('fecha_consignacion', '<=', $fechaHasta);
        }

        $consignaciones = $query->get();

        foreach ($consignaciones as $consig) {
            $interes = round($consig->valor_consignado * ($rate / 100), 2);
            $consig->update([
                'tasa_aplicada' => $rate,
                'interes_aplicado' => $interes,
                'total_con_interes' => round($consig->valor_consignado + $interes, 2),
                'interes_aplicado_by' => auth()->id(),
                'interes_aplicado_at' => now(),
            ]);
        }

        $count = $consignaciones->count();

        return redirect()->back()->with('success', $count === 1
            ? "Interés del {$rate}% aplicado a 1 consignación."
            : "Interés del {$rate}% aplicado a {$count} consignaciones.");
    }
}
