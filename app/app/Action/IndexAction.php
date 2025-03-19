<?php
declare(strict_types=1);

namespace App\Action;

use Illuminate\Http\Request;
use App\Action\Requests\RequestInterface;
use App\Action\Requests\GetPetsByStatusRequest;

class IndexAction extends AbstractRequestAction
{
    protected function getAppUri(): string
    {
        return '/';
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
            'available'
        );
    }
}
