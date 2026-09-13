<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ComprobanteStorage
{
    /**
     * Resolve the disk to use for comprobantes, falling back to public
     * when B2 is configured but has no credentials, or local is selected.
     */
    public static function disk(): string
    {
        $disk = config('filesystems.comprobantes_disk', 'public');

        if ($disk === 'b2' && ! config('filesystems.disks.b2.key')) {
            return 'public';
        }

        if ($disk === 'local') {
            return 'public';
        }

        return $disk;
    }

    /**
     * Build a temporary/asset URL for a stored comprobante path.
     */
    public static function url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        $disk = self::disk();

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
}
