<?php

namespace App\Concerns;

use Illuminate\Support\Facades\Storage;

/**
 * Hapus file fisik di disk 'public' saat baris dihapus,
 * dan sediakan helper untuk menghapus file lama saat diganti.
 * Model cukup deklarasikan: protected static array $storedFiles = ['gambar'];
 */
trait DeletesStoredFiles
{
    public static function bootDeletesStoredFiles(): void
    {
        static::deleting(function ($model) {
            foreach ($model::storedFileFields() as $field) {
                $model->deleteStoredFile($model->getRawOriginal($field));
            }
        });
    }

    /** @return string[] */
    public static function storedFileFields(): array
    {
        return property_exists(static::class, 'storedFiles') ? (array) static::$storedFiles : [];
    }

    public function deleteStoredFile(mixed $raw): void
    {
        if (! is_string($raw) || $raw === '') return;
        $path = ltrim(preg_replace('#^/?storage/#', '', $raw), '/');
        if ($path === '') return;
        try {
            Storage::disk('public')->delete($path);
        } catch (\Throwable $e) {
        }
    }
}
