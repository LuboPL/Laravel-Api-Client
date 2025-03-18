<?php
declare(strict_types=1);

namespace App\Http\Requests;

use App\Config\ConfigInterface;
use App\Models\Pet;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

readonly class PetStoreRequest
{
    public function __construct(private ConfigInterface $config)
    {
    }

    public function getPetsByStatus($status): Response
    {
        return Http::get(sprintf('%s/%s',
            $this->config->getApiUrl(),
            $this->config->getEndpointFindByStatus()
            ), ['status' => $status]
        );
    }

    public function postNewPet(Pet $pet): Response
    {
        return Http::post($this->config->getApiUrl(), $pet->getPreparedData());
    }

    public function getPetById(int $id): Response
    {
        return Http::get(sprintf('%s/%s',
                $this->config->getApiUrl(),
                $id
            )
        );
    }

    public function putPet(Pet $pet): Response
    {
        return Http::put($this->config->getApiUrl(), $pet->getPreparedData());
    }

//    public function deletePetById(int $id): Response
//    {
//        return Http::delete(sprintf('%s/%s',))
//    }
}
