<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use thiagoalessio\TesseractOCR\TesseractOCR;

class OcrService
{
    /**
     * Process a comprobante image and extract data using OCR.
     * Returns null if OCR fails or no engine is configured.
     */
    public function procesar(UploadedFile $file): ?array
    {
        $engine = config('services.ocr.engine', 'none');

        return match ($engine) {
            'tesseract' => $this->procesarConTesseract($file),
            default => null,
        };
    }

    private function procesarConTesseract(UploadedFile $file): ?array
    {
        if (! class_exists(TesseractOCR::class)) {
            return null;
        }

        $tempPath = $file->store('tmp', 'local');
        $fullPath = storage_path('app/private/'.$tempPath);

        try {
            $text = (new TesseractOCR($fullPath))
                ->lang('spa')
                ->run();

            return $this->parsearTexto($text);
        } catch (\Exception) {
            return null;
        } finally {
            if (isset($tempPath)) {
                Storage::disk('local')->delete($tempPath);
            }
        }
    }

    /**
     * Parse raw OCR text to extract structured data.
     */
    private function parsearTexto(string $text): array
    {
        $datos = [];

        // Try to find a date pattern (dd/mm/yyyy, dd-mm-yyyy, etc.)
        if (preg_match('/(\d{1,2}[\/\-\.]\d{1,2}[\/\-\.]\d{2,4})/', $text, $m)) {
            $datos['fecha'] = $m[1];
        }

        // Try to find monetary values ($ 500.000, $1.000.000, 500000)
        if (preg_match('/\$?\s*(\d{1,3}(?:\.\d{3})*(?:,\d{2})?)/', $text, $m)) {
            $valor = (float) str_replace(['.', ','], ['', '.'], $m[1]);
            if ($valor > 0) {
                $datos['valor'] = $valor;
            }
        }

        // Try to find bank names
        $bancos = ['Bancolombia', 'Davivienda', 'BBVA', 'Banco de Bogotá', 'Banco Popular', 'Colpatria', 'AV Villas', 'Banco Agrario'];
        foreach ($bancos as $banco) {
            if (stripos($text, $banco) !== false) {
                $datos['banco'] = $banco;
                break;
            }
        }

        // Try to find a reference number
        if (preg_match('/(?:referencia|ref|#)\s*[:\s]*(\d{4,})/i', $text, $m)) {
            $datos['referencia'] = $m[1];
        }

        return $datos;
    }
}
