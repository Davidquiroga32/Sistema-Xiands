<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConsignacionRequest;
use App\Http\Requests\UpdateConsignacionRequest;
use App\Models\Consignacion;
use App\Models\Persona;
use App\Services\ComprobanteStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ConsignacionController extends Controller
{
    private function disk(): string
    {
        return ComprobanteStorage::disk();
    }

    private function comprobanteUrl(?string $path): ?string
    {
        return ComprobanteStorage::url($path);
    }

    public function index(): View
    {
        return view('consignaciones.index');
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
            $data['comprobante_path'] = rescue(
                fn () => Storage::disk($this->disk())->putFile('comprobantes', $file),
                fn () => $file->store('comprobantes', 'public'),
                false,
            );
            $data['comprobante_tipo'] = str_contains($file->getClientMimeType(), 'pdf') ? 'pdf' : 'imagen';
        }

        $consignacion = Consignacion::create($data);

        $persona = $consignacion->persona;
        if ($persona && $persona->tasa_interes > 0) {
            $interes = round($consignacion->valor_consignado * ($persona->tasa_interes / 100), 2);
            $consignacion->update([
                'tasa_aplicada' => $persona->tasa_interes,
                'interes_aplicado' => $interes,
                'total_con_interes' => round($consignacion->valor_consignado + $interes, 2),
                'interes_aplicado_by' => auth()->id(),
                'interes_aplicado_at' => now(),
            ]);
        }

        return redirect()
            ->route('consignaciones.show', $consignacion)
            ->with('success', 'Consignación registrada exitosamente.');
    }

    public function show(Consignacion $consignacion): View
    {
        $consignacion->load('persona', 'creador', 'interesAplicadoPor');

        $comprobanteUrl = $this->comprobanteUrl($consignacion->comprobante_path);

        return view('consignaciones.show', compact('consignacion', 'comprobanteUrl'));
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
                Storage::disk($this->disk())->delete($consignacion->comprobante_path);
            }
            $file = $request->file('comprobante');
            $data['comprobante_path'] = Storage::disk($this->disk())->putFile('comprobantes', $file);
            $data['comprobante_tipo'] = str_contains($file->getClientMimeType(), 'pdf') ? 'pdf' : 'imagen';
        }

        $consignacion->update($data);

        return redirect()
            ->route('consignaciones.show', $consignacion)
            ->with('success', 'Consignación actualizada exitosamente.');
    }

    public function destroy(Consignacion $consignacion): RedirectResponse
    {
        $this->authorize('delete', $consignacion);

        if ($consignacion->comprobante_path) {
            Storage::disk($this->disk())->delete($consignacion->comprobante_path);
        }

        $consignacion->delete();

        return redirect()
            ->route('consignaciones.index')
            ->with('success', 'Consignación eliminada exitosamente.');
    }

    public function aplicarInteres(Consignacion $consignacion): RedirectResponse
    {
        $tasa = (float) ($consignacion->persona?->tasa_interes ?: 5);
        $interes = round($consignacion->valor_consignado * ($tasa / 100), 2);

        $consignacion->update([
            'tasa_aplicada' => $tasa,
            'interes_aplicado' => $interes,
            'total_con_interes' => round($consignacion->valor_consignado + $interes, 2),
            'interes_aplicado_by' => auth()->id(),
            'interes_aplicado_at' => now(),
        ]);

        return redirect()->back()->with('success', "Interés del {$tasa}% aplicado exitosamente.");
    }

    public function toggleEstado(Consignacion $consignacion): RedirectResponse
    {
        $consignacion->toggleEstado();

        return redirect()->back()->with('success', 'Estado actualizado a '.($consignacion->estado === 'vigente' ? 'vigente' : 'finalizada').'.');
    }
}
