<?php
declare(strict_types=1);

namespace App\Action\Requests;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

readonly class DeletePetRequest implements RequestInterface
{
    public function __construct(
        private int $petId,
        private string $apiUrl,
        private string $status,
        private string $apiKey
    )
    {
    }

    /**
     * @throws ConnectionException
     */
    public function create(): Response
    {
        return Http::withHeaders(['api_key' => $this->apiKey])
            ->delete($this->getUri());
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
