<?php

namespace App\Helpers;

class StringHelper
{
    public static function hyphenated(string $inputString = ''): array|string
    {
        return str_replace([' '], '-', $inputString);
    }
}
