<?php
declare(strict_types=1);

namespace App\Action;

use App\Action\Requests\AddNewPetRequest;
use App\Config\ConfigInterface;
use App\Service\PetPayloadMapper;
use Illuminate\Http\Request;
use App\Action\Requests\RequestInterface;
use Random\RandomException;

class AddPetAction extends AbstractRequestAction
{
    public function __construct(
        ConfigInterface $config,
        private readonly PetPayloadMapper $payloadMapper
    )
    {
        parent::__construct($config);
    }
    protected function getRouteName(): string
    {
        return 'pets.store';
    }

    protected function getMethod(): string
    {
        return 'POST';
    }

    /**
     * @throws RandomException
     */
    public function createRequest(Request $request): RequestInterface
    {
        return new AddNewPetRequest(
            $this->config->getApiUrl(),
            $this->config->getEndpointFindByStatus(),
            $this->getData($request),
        );
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
