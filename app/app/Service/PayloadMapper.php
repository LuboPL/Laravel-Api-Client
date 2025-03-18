<?php
declare(strict_types=1);

namespace App\Service;

use App\Models\Category;
use App\Models\Pet;
use App\Models\Tag;
use Illuminate\Http\Request;
use Random\RandomException;

class PayloadMapper
{
    /**
     * @throws RandomException
     */
    public function mapPetFromRequest(Request $request): Pet
    {
        $payload = $request->toArray();

        return new Pet(
            Int64Generator::generate(),
            new Category(Int64Generator::generate(), $payload['category']),
            $payload['name'],
            $payload['photoUrls'],
            $this->mapTags($payload['tags']),
            $payload['status'],
        );
    }


    /**
     * @throws RandomException
     */
    private function mapTags(array $tags): array
    {
        return array_map(
         fn(array $tag) => new Tag(
                id: Int64Generator::generate(),
                name: $tag['name']
            ),
            $tags
        );
    }
}
