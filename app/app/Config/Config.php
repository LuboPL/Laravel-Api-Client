<?php
declare(strict_types=1);

namespace App\Config;

class Config implements ConfigInterface
{

    public function getApiKey(): string
    {
        return self::API_KEY;
    }

    public function getApiUrl(): string
    {
        return self::API_URL;
    }

    public function getEndpointFindByStatus(): string
    {
        return self::ENDPOINT_FIND_BY_STATUS;
    }

    public function getAvailableStatuses(): array
    {
        return self::AVAILABLE_STATUSES;
    }
}
