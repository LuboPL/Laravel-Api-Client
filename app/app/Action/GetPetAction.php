<?php
declare(strict_types=1);

namespace App\Action;

use App\Action\Requests\GetPetRequest;
use Illuminate\Http\Request;
use App\Action\Requests\RequestInterface;

class GetPetAction extends AbstractRequestAction
{
    protected function getRouteName(): string
    {
        return 'pets.edit';
    }

    protected function getMethod(): string
    {
        return 'GET';
    }

    public function createRequest(Request $request): RequestInterface
    {
        return new GetPetRequest(
            (int)$request->route()->parameter('pet'),
            $this->config->getApiUrl(),
            $request->get('status') ?? ''
        );
    }
}
