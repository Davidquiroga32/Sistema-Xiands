<?php

namespace App\Http\Controllers;

use App\Models\Consignacion;
use App\Services\ComprobanteStorage;
use App\Services\OcrService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComprobanteController extends Controller
{
    public function show(Consignacion $consignacion): RedirectResponse
    {
        if (! $consignacion->comprobante_path) {
            abort(404);
        }

        $disk = ComprobanteStorage::disk();

        if ($disk === 'b2') {
            $url = Storage::disk('b2')->temporaryUrl(
                $consignacion->comprobante_path,
                now()->addMinutes(30),
            );

            return redirect()->away($url);
        }

        return redirect()->to(asset('storage/'.$consignacion->comprobante_path));
    }

    public function procesar(Request $request, OcrService $ocr): JsonResponse
    {
        $request->validate([
            'comprobante' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $resultado = $ocr->procesar($request->file('comprobante'));

        if ($resultado === null) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudieron extraer datos del comprobante.',
            ]);
        }

        return response()->json([
            'success' => true,
            'datos' => $resultado,
        ]);
    }
}
