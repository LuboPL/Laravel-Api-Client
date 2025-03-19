<?php
declare(strict_types=1);

namespace App\Actions;

use App\Actions\Requests\UploadPetImageRequest;
use Illuminate\Http\Request;
use App\Actions\Requests\RequestInterface;

class UploadPetImageAction extends AbstractRequestAction
{
    protected function getRouteName(): string
    {
        return 'pets.uploadImage';
    }

    protected function getMethod(): string
    {
        return 'POST';
    }

    public function createRequest(Request $request): RequestInterface
    {
        return new UploadPetImageRequest(
            (int)$request->route()->parameter('petId'),
            $this->config->getApiUrl(),
            '',
            $this->getFilePath($request),
            $request->get('additionalMetadata')
        );
    }

    private function getFilePath(Request $request): ?string
    {
        $uploadedFile = $request->file('pet_image');

        return sprintf(
            '/var/www/project/storage/app/public/%s',
            $uploadedFile->store('pet_images', 'public')
        );
    }
}
