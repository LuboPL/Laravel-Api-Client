<?php
declare(strict_types=1);

namespace App\Actions\Registry;

use App\Actions\AddPetAction;
use App\Actions\DeletePetAction;
use App\Actions\GetPetAction;
use App\Actions\GetPetsByStatusAction;
use App\Actions\RequestActionInterface;
use App\Actions\UpdatePetAction;
use App\Actions\UpdatePetWithFormDataAction;
use App\Actions\UploadPetImageAction;
use App\Config\ConfigInterface;
use App\Exception\PetStoreException;
use App\Service\PetPayloadMapper;
use Illuminate\Http\Request;

class RequestActionRegistry
{
    /**
     * @var array<RequestActionInterface>
     */
    private array $actions = [];

    public function __construct(
        ConfigInterface $config,
        private readonly PetPayloadMapper $payloadMapper
    )
    {
        $this->registerActions($config);
    }

    private function registerActions(ConfigInterface $config): void
    {
        $this->actions = [
            new GetPetsByStatusAction($config),
            new GetPetAction($config),
            new AddPetAction($config, $this->payloadMapper),
            new UpdatePetAction($config, $this->payloadMapper),
            new DeletePetAction($config),
            new UploadPetImageAction($config),
            new UpdatePetWithFormDataAction($config)
        ];
    }

    /**
     * @throws PetStoreException
     */
    public function findAction(Request $request): RequestActionInterface
    {
        foreach ($this->actions as $action) {
            if ($action->matches($request)) {
                return $action;
            }
        }

        throw new PetStoreException('Route not found or method not allowed');
    }
}
