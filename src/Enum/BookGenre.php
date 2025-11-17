<?php

namespace App\Enum;

enum BookGenre: string
{
    case FANTASY = 'FANTASY';
    case SCIFI = 'SCIFI';
    case DRAMA = 'DRAMA';
    case NONFICTION = 'NONFICTION';
    case OTHER = 'OTHER';

    public static function values(): array
    {
        return array_map(static fn (self $case) => $case->value, self::cases());
    }
}
