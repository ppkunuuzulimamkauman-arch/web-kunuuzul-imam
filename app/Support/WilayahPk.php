<?php

namespace App\Support;

class WilayahPk
{
    /**
     * Pemetaan kecamatan Bondowoso ke wilayah PK.
     * Sumber: poster "Mengetahui Wilayah/PK".
     *
     * @var array<string, string>
     */
    public const BONDOWOSO = [
        'binakal' => 'PK 1',
        'curahdami' => 'PK 1',
        'tenggarang' => 'PK 3',
        'tegalampel' => 'PK 3',
        'taman krocok' => 'PK 3',
        'pujer' => 'PK 4',
        'tlogosari' => 'PK 4',
        'sukosari' => 'PK 4',
        'wonosari' => 'PK 4',
        'tapen' => 'PK 4',
        'sempol' => 'PK 4',
        'ijen' => 'PK 4',
        'maesan' => 'PK 5',
        'grujugan' => 'PK 5',
        'pakem' => 'PK 6',
        'wringin' => 'PK 6',
        'tamanan' => 'PK 7',
        'jambesari darus sholah' => 'PK 7',
        'jambesari ds' => 'PK 7',
        'jambesari' => 'PK 7',
    ];

    /** @var array<int, string> */
    public const KECAMATAN = [
        'Binakal',
        'Curahdami',
        'Tenggarang',
        'Tegalampel',
        'Taman Krocok',
        'Pujer',
        'Tlogosari',
        'Sukosari',
        'Wonosari',
        'Tapen',
        'Sempol',
        'Ijen',
        'Maesan',
        'Grujugan',
        'Pakem',
        'Wringin',
        'Tamanan',
        'Jambesari Darus Sholah',
    ];

    public static function for(?string $kabupaten, ?string $kecamatan): ?string
    {
        $kabupaten = self::normalize($kabupaten);
        $kecamatan = self::normalize($kecamatan);

        if ($kabupaten === 'jember') {
            return 'PK 2';
        }

        if ($kabupaten === 'bondowoso') {
            return self::BONDOWOSO[$kecamatan] ?? null;
        }

        return null;
    }

    /** @return array<string, string> */
    public static function map(): array
    {
        return self::BONDOWOSO;
    }

    /** @return array<int, string> */
    public static function kabupatenOptions(): array
    {
        return ['Bondowoso', 'Jember'];
    }

    /** @return array<int, string> */
    public static function kecamatanOptions(): array
    {
        return self::KECAMATAN;
    }

    /** @return array<int, string> */
    public static function pkOptions(): array
    {
        return ['PK 1', 'PK 2', 'PK 3', 'PK 4', 'PK 5', 'PK 6', 'PK 7'];
    }

    public static function label(?string $pk): string
    {
        return $pk ? 'Wilayah '.$pk : 'Belum ditentukan';
    }

    public static function normalize(?string $value): string
    {
        $value = strtolower(trim((string) $value));
        $value = preg_replace('/\b(kecamatan|kec|kabupaten|kab|kota)\b/u', ' ', $value) ?? $value;
        $value = preg_replace('/[^a-z0-9]+/u', ' ', $value) ?? $value;

        return trim(preg_replace('/\s+/', ' ', $value) ?? $value);
    }
}
