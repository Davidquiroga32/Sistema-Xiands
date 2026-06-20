<?php

namespace App\Http\Controllers;

use App\Models\Consignacion;
use App\Models\Persona;
use App\Http\Requests\StoreConsignacionRequest;
use App\Http\Requests\UpdateConsignacionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ConsignacionController extends Controller
{
    public function index(): View
    {
        $consignaciones = Consignacion::with('persona')->latest()->paginate(15);

        return view('consignaciones.index', compact('consignaciones'));
    }

    public function create(): View
    {
        $personas = Persona::orderBy('nombre_completo')->get();

        return view('consignaciones.create', compact('personas'));
    }

    public function store(StoreConsignacionRequest $request): RedirectResponse
    {
        $data = [
            ...$request->validated(),
            'created_by' => auth()->id(),
        ];

        if ($request->hasFile('comprobante')) {
            $file = $request->file('comprobante');
            $data['comprobante_path'] = $file->store('comprobantes');
            $data['comprobante_tipo'] = str_contains($file->getClientMimeType(), 'pdf') ? 'pdf' : 'imagen';
        }

        $consignacion = Consignacion::create($data);

        return redirect()
            ->route('consignaciones.show', $consignacion)
            ->with('success', 'Consignación registrada exitosamente.');
    }

    public function show(Consignacion $consignacion): View
    {
        $consignacion->load('persona');

        return view('consignaciones.show', compact('consignacion'));
    }

    public function edit(Consignacion $consignacion): View
    {
        $personas = Persona::orderBy('nombre_completo')->get();

        return view('consignaciones.edit', compact('consignacion', 'personas'));
    }

    public function update(UpdateConsignacionRequest $request, Consignacion $consignacion): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('comprobante')) {
            if ($consignacion->comprobante_path) {
                Storage::delete($consignacion->comprobante_path);
            }
            $file = $request->file('comprobante');
            $data['comprobante_path'] = $file->store('comprobantes');
            $data['comprobante_tipo'] = str_contains($file->getClientMimeType(), 'pdf') ? 'pdf' : 'imagen';
        }

        $consignacion->update($data);

        return redirect()
            ->route('consignaciones.show', $consignacion)
            ->with('success', 'Consignación actualizada exitosamente.');
    }

    public function destroy(Consignacion $consignacion): RedirectResponse
    {
        $consignacion->delete();

        return redirect()
            ->route('consignaciones.index')
            ->with('success', 'Consignación eliminada exitosamente.');
    }

    public function aplicarInteres(Consignacion $consignacion): RedirectResponse
    {
        $interes = round($consignacion->valor_consignado * 0.05, 2);

        $consignacion->update([
            'interes_aplicado' => $interes,
            'total_con_interes' => round($consignacion->valor_consignado + $interes, 2),
            'interes_aplicado_by' => auth()->id(),
            'interes_aplicado_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Interés del 5% aplicado exitosamente.');
    }
}
