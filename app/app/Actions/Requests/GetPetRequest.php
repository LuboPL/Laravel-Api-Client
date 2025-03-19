<?php
declare(strict_types=1);

namespace App\Actions\Requests;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

readonly class GetPetRequest implements RequestInterface
{
    public function __construct(
        private int $petId,
        private string $apiUrl,
        private string $status,
    )
    {
    }

    public function create(): Response
    {
        return Http::get($this->getUri());
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
