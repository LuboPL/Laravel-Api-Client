<?php
declare(strict_types=1);

namespace App\Actions;

use App\Actions\Requests\UpdatePetWithFormRequest;
use Illuminate\Http\Request;
use App\Actions\Requests\RequestInterface;

class UpdatePetWithFormDataAction extends AbstractRequestAction
{
    protected function getRouteName(): string
    {
        return 'pets.updateWithForm';
    }

    protected function getMethod(): string
    {
        return 'POST';
    }

    public function createRequest(Request $request): RequestInterface
    {
        return new UpdatePetWithFormRequest(
            (int)$request->route()->parameter('petId'),
            $this->config->getApiUrl(),
            '',
            $this->getData($request),
        );
    }

    private function getData(Request $request): array
    {
        return [
            'name' => $request->input('name'),
            'status' => $request->input('status'),
        ];
    }
}
