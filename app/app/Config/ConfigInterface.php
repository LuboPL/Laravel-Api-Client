<?php
declare(strict_types=1);

namespace App\Config;

interface ConfigInterface
{
    public const API_URL = 'https://api.swagger.io/v2/pet';
    public const API_KEY = 'special-key';
    public const ENDPOINT_FIND_BY_STATUS = 'findByStatus';
}
