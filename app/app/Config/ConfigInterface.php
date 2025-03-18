<?php
declare(strict_types=1);

namespace App\Config;

interface ConfigInterface
{
    const API_URL = 'https://petstore.swagger.io/v2/pet';
    const API_KEY = 'special-key';
    const ENDPOINT_FIND_BY_STATUS = 'findByStatus';
    const AVAILABLE_STATUSES = ['available', 'pending', 'sold'];

    public function getApiKey(): string;
    public function getApiUrl(): string;
    public function getEndpointFindByStatus(): string;
    public function getAvailableStatuses(): array;
}
