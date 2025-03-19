<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

readonly class GetPetsByStatusRequest implements RequestInterface
{
    public function __construct(
        private string $method,
        private string $apiUrl,
        private string $apiEndpoint,
        private string $status
    )
    {
    }

    public function create(): Response
    {
        return Http::get(
            $this->getUri(),
            ['status' => $this->status]
        );
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return sprintf(
            '%s/%s',
            $this->apiUrl,
            $this->apiEndpoint
        );
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
