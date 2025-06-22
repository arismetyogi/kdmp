<?php

namespace App\Enums;

enum Reasons: string
{
    case APOTEK = 'Apotek belum kirim alat tagih';
    case BM = 'BM belum tanda tangan alat tagih';
    case POS = 'Perbedaan bulan transaksi POS dengan bulan klaim';

    public static function labels(): array
    {
        return [
            self::APOTEK->value => 'Apotek belum kirim alat tagih',
            self::BM->value => 'BM belum tanda tangan alat tagih',
            self::POS->value => 'Perbedaan bulan transaksi POS dengan bulan klaim',
        ];
    }

    public static function fromName(?string $name): ?self
    {
        foreach (self::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }

        return null;
    }
}
