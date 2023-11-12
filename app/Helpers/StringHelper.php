<?php

namespace App\Helpers;

class StringHelper
{
    public static function hyphenated(string $inputString = ''): array|string
    {
        return str_replace([' '], '-', $inputString);
    }

    public static function errorMessage(): string
    {
        return 'There was an error with your submission. Please check the form and try again.';
    }
}
