<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConsignacionRequest;
use App\Http\Requests\UpdateConsignacionRequest;
use App\Models\Consignacion;
use App\Models\Persona;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ConsignacionController extends Controller
{
    private function disk(): string
    {
        $disk = config('filesystems.comprobantes_disk', 'b2');

        // Fallback to public if B2/Local is not configured or unreachable
        if ($disk === 'b2' && ! config('filesystems.disks.b2.key')) {
            return 'public';
        }

        if ($disk === 'local') {
            return 'public';
        }

        return $disk;
    }

    private function comprobanteUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        $disk = $this->disk();

        if ($disk === 'b2') {
            $url = rescue(
                fn () => Storage::disk('b2')->temporaryUrl($path, now()->addMinutes(30)),
                null,
                false,
            );

            if ($url) {
                return $url;
            }
        }

        return asset('storage/'.$path);
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
        $interes = round($consignacion->valor_consignado * 0.05, 2);

        $consignacion->update([
            'tasa_aplicada' => 5.00,
            'interes_aplicado' => $interes,
            'total_con_interes' => round($consignacion->valor_consignado + $interes, 2),
            'interes_aplicado_by' => auth()->id(),
            'interes_aplicado_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Interés del 5% aplicado exitosamente.');
    }

    public function toggleEstado(Consignacion $consignacion): RedirectResponse
    {
        $consignacion->toggleEstado();

        return redirect()->back()->with('success', 'Estado actualizado a '.($consignacion->estado === 'vigente' ? 'vigente' : 'finalizada').'.');
    }
}
