<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

readonly class DeletePetRequest implements RequestInterface
{
    public function __construct(
        private int $petId,
        private string $method,
        private string $apiUrl,
        private string $status,
    )
    {
    }

    public function create(): Response
    {
        return Http::delete($this->getUri());
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return sprintf('%s/%d',
            $this->apiUrl,
            $this->petId
        );
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
