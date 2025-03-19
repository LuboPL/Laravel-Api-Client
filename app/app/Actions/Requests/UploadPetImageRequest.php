<?php
declare(strict_types=1);

namespace App\Actions\Requests;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

readonly class UploadPetImageRequest implements RequestInterface
{
    public function __construct(
        private int $petId,
        private string $apiUrl,
        private string $status,
        private string $imagePath,
        private ?string $additionalMetadata = null
    )
    {
    }

    /**
     * @throws ConnectionException
     */
    public function create(): Response
    {
        $request = Http::asMultipart();

        $request = $request->attach('petId', $this->petId);

        if ($this->additionalMetadata !== null) {
            $request = $request->attach('additionalMetadata', $this->additionalMetadata);
        }

        return $request->attach('file', fopen($this->imagePath, 'r'), basename($this->imagePath))
            ->post($this->getUri());
    }

    public function getUri(): string
    {
        return sprintf('%s/%s/uploadImage', $this->apiUrl, $this->petId);
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
