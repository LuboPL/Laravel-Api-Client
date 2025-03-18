<?php
declare(strict_types=1);

namespace App\Service;

use App\Models\Category;
use App\Models\Pet;
use App\Models\Tag;
use Illuminate\Http\Client\Response;
use Random\RandomException;

class PayloadMapper
{
    /**
     * @throws RandomException
     */
    public function mapPetFromArray(array $payload): Pet
    {
        return new Pet(
            !array_key_exists('id', $payload) ? Int64Generator::generate() : $payload['id'],
            new Category(Int64Generator::generate(), $payload['category']['name']),
            $payload['name'],
            $payload['photoUrls'],
            $this->mapTags($payload['tags']),
            $payload['status'],
        );
    }

    /**
     * @throws RandomException
     */
    public function mapPetsFromResponse(Response $response): array
    {
        $data = $response->json();
        $pets = [];
        foreach ($data as $pet) {
            if (!array_key_exists('category', $pet)) {
                continue;
            }
            $categoryName = array_key_exists('name', $pet['category']) ? $pet['category']['name'] : '';
            $petName = array_key_exists('name', $pet) ? $pet['name'] : '';
            $pets[] = new Pet(
                $pet['id'],
                new Category($pet['category']['id'], $categoryName),
                $petName,
                $pet['photoUrls'],
                $this->mapTags($pet['tags']),
                $pet['status'],
            );
        }

        return $pets;
    }

    /**
     * @throws RandomException
     */
    private function mapTags(array $tags): array
    {
        return array_map(
         fn(array $tag) => new Tag(
                id: is_null($tag['id']) ? Int64Generator::generate() : $tag['id'],
                name: array_key_exists('name', $tag) ? $tag['name'] : '',
            ),
            $tags
        );
    }
}
