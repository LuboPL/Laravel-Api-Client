<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

readonly class AddNewPetRequest implements RequestInterface
{
    public function __construct(
        private string $method,
        private string $apiUrl,
        private string $status,
        private array $data
    )
    {
    }

    public function create(): Response
    {
        return Http::post($this->apiUrl, $this->data);
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->apiUrl;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}

