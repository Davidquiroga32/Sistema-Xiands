<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdatePersonaRequest;
use Illuminate\Http\RedirectResponse;
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
        $persona->delete();

        return redirect()
            ->route('personas.index')
            ->with('success', 'Persona eliminada exitosamente.');
    }
}
