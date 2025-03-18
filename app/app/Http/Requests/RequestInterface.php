<?php
declare(strict_types=1);

namespace App\Http\Requests;

use App\Exception\PetStoreException;
use Illuminate\Http\Client\Response;

interface RequestInterface
{
    /**
     * @throws PetStoreException
     */
    public function create(): Response;
    public function getMethod(): string;
    public function getUri(): string;
    public function getStatus(): string;
}
