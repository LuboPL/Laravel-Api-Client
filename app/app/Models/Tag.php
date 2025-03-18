<?php
declare(strict_types=1);

namespace App\Models;

readonly class Tag
{
    public function __construct(public int $id, public string $name)
    {
    }
}
