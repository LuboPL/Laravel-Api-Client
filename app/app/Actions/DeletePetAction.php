<?php
declare(strict_types=1);

namespace App\Actions;

use App\Actions\Requests\DeletePetRequest;
use Illuminate\Http\Request;
use App\Actions\Requests\RequestInterface;

class DeletePetAction extends AbstractRequestAction
{
    protected function getRouteName(): string
    {
        return 'pets.destroy';
    }

    protected function getMethod(): string
    {
        return 'DELETE';
    }

    public function createRequest(Request $request): RequestInterface
    {
        return new DeletePetRequest(
            (int)$request->route()->parameter('pet'),
            $this->config->getApiUrl(),
            $request->get('status') ?? '',
            $this->config->getApiKey()
        );
    }
}
