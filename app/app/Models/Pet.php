<?php
declare(strict_types=1);

namespace App\Models;

readonly class Pet
{
    public function __construct(
        public int $id,
        public Category $category,
        public string $name,
        public array $photoUrls,
        /**
         * @var array<Tag>
         */
        public array $tags,
        public string $status
    )
    {
    }

    public function getPreparedData(): array
    {
        return [
            'id' => $this->id,
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->name
            ],
            'name' => $this->name,
            'photoUrls' => $this->photoUrls,
            'tags' => array_map(
                fn(Tag $tag) => ['id' => $tag->id, 'name' => $tag->name],
                $this->tags
            ),
            'status' => $this->status
        ];
    }
}
