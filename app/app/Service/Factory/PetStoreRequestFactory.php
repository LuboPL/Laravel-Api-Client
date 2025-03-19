<?php
declare(strict_types=1);

namespace App\Service\Factory;

use App\Config\ConfigInterface;
use App\Exception\PetStoreException;
use App\Http\Requests\AddNewPetRequest;
use App\Http\Requests\DeletePetRequest;
use App\Http\Requests\GetPetRequest;
use App\Http\Requests\GetPetsByStatusRequest;
use App\Http\Requests\RequestInterface;
use App\Http\Requests\UpdatePetRequest;
use App\Service\PayloadMapper;
use Illuminate\Http\Request;
use Random\RandomException;

readonly class PetStoreRequestFactory implements RequestFactoryInterface
{
    public function __construct(
        private ConfigInterface $config,
        private PayloadMapper $payloadMapper
    )
    {
    }

    /**
     * @throws PetStoreException
     * @throws RandomException
     */
    public function create(Request $request): RequestInterface
    {
        $uri = $request->route()->uri();
        $method = $request->method();

        return match ($uri) {
            'pets' => match ($method) {
                'GET' => new GetPetsByStatusRequest(
                    $method,
                    $this->config->getApiUrl(),
                    $this->config->getEndpointFindByStatus(),
                    $request->input('status', 'available')
                ),
                'POST' => new AddNewPetRequest(
                    $method,
                    $this->config->getApiUrl(),
                    $request->get('status'),
                    $this->getData($request)
                ),
                default => throw new PetStoreException('Method not allowed'),
            },
            'pets/{pet}/edit' => match ($request->method()) {
                'GET' => new GetPetRequest(
                    (int)$request->route()->parameter('pet'),
                    $method,
                    $this->config->getApiUrl(),
                    $request->get('status') ?? ''
                ),
                default => throw new PetStoreException('Method not allowed'),
            },
            'pets/{pet}' => match ($request->method()) {
                'PUT' => new UpdatePetRequest(
                    $method,
                    $this->config->getApiUrl(),
                    $request->get('status') ?? '',
                    $this->getData($request)
                ),
                'DELETE' => new DeletePetRequest(
                    (int)$request->route()->parameter('pet'),
                    $method,
                    $this->config->getApiUrl(),
                    $request->get('status') ?? '',
                    $this->config->getApiKey()
                ),
                default => throw new PetStoreException('Method not allowed'),
            },
            '/' => new GetPetsByStatusRequest(
                $request->method(),
                $this->config->getApiUrl(),
                $this->config->getEndpointFindByStatus(),
                'available'
            ),
            default => throw new PetStoreException('Route not found'),
        };
    }

    /**
     * @throws RandomException
     */
    private function getData(Request $request): array
    {
        $payload = $request->toArray();
        $pet = $this->payloadMapper->mapPetFromArray($payload);
        return $pet->getPreparedData();
    }
}
