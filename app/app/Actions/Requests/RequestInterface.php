<?php
declare(strict_types=1);

namespace App\Actions\Requests;

use App\Exception\PetStoreException;
use Illuminate\Http\Client\Response;

interface RequestInterface
{
    /**
     * @throws PetStoreException
     */
    public function create(): Response;
    public function getUri(): string;
    public function getStatus(): string;
}
