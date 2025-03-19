<?php
declare(strict_types=1);

namespace App\Actions\Requests;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

readonly class UpdatePetWithFormRequest implements RequestInterface
{
    public function __construct(
        private int $petId,
        private string $apiUrl,
        private string $status,
        private array $data
    )
    {
    }

    /**
     * @throws ConnectionException
     */
    public function create(): Response
    {
        return Http::asForm()->post($this->getUri(), $this->data);
    }

    public function getUri(): string
    {
        return sprintf('%s/%s', $this->apiUrl, $this->petId);
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
