<?php

namespace App\Enums;

enum StatusTypes: string {
    case Active = "active";
    case Inactive = "inactive";

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}