<?php
declare(strict_types=1);

namespace App\Service;

use Random\RandomException;

class Int64Generator
{
    /**
     * @throws RandomException
     */
    public static function generate(): int
    {
        return random_int(0, PHP_INT_MAX);
    }
}
