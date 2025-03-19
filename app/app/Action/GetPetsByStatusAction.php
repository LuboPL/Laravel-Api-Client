<?php
declare(strict_types=1);

namespace App\Action;

use Illuminate\Http\Request;
use App\Action\Requests\RequestInterface;
use App\Action\Requests\GetPetsByStatusRequest;

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
