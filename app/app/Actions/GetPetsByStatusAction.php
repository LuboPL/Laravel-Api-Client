<?php
declare(strict_types=1);

namespace App\Actions;

use Illuminate\Http\Request;
use App\Actions\Requests\RequestInterface;
use App\Actions\Requests\GetPetsByStatusRequest;

class GetPetsByStatusAction extends AbstractRequestAction
{
    protected function getRouteName(): string
    {
        return 'pets.index';
    }

    protected function getMethod(): string
    {
        return 'GET';
    }

    public function createRequest(Request $request): RequestInterface
    {
        return new GetPetsByStatusRequest(
            $this->config->getApiUrl(),
            $this->config->getEndpointFindByStatus(),
            $request->input('status', 'available')
        );
    }
}
