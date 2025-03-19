<?php
declare(strict_types=1);

namespace App\Validator;

use Illuminate\Http\Request;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

class PetPayloadValidator
{
    /**
     * @throws InvalidArgumentException
     */
    public function validateNewPetPayload(Request $request): void
    {
        $payload = $request->toArray();

        Assert::keyExists($payload, 'category', 'Field "category" is required');
        Assert::notEmpty($payload['category'], 'Field "category" cannot be empty');

        Assert::keyExists($payload, 'name', 'Field "name" is required');
        Assert::string($payload['name'], 'Field "name" must be a string');
        Assert::notEmpty($payload['name'], 'Field "name" cannot be empty');

        Assert::keyExists($payload, 'photoUrls', 'Field "photoUrls" is required');
        Assert::isArray($payload['photoUrls'], 'Field "photoUrls" must be an array');
        Assert::allString(
            $payload['photoUrls'],
            'All values in "photoUrls" must be valid URLs (strings)'
        );

        Assert::keyExists($payload, 'tags', 'Field "tags" is required');
        Assert::isArray($payload['tags'], 'Field "tags" must be an array');
        foreach ($payload['tags'] as $index => $tag) {
            Assert::isArray(
                $tag,
                sprintf('Tag at position %d must be an object', $index)
            );
            Assert::keyExists(
                $tag,
                'name',
                sprintf('Tag at position %d must have a "name" field', $index)
            );
            Assert::string(
                $tag['name'],
                sprintf('Tag name at position %d must be a string', $index)
            );
        }

        Assert::keyExists($payload, 'status', 'Field "status" is required');
        Assert::string($payload['status'], 'Field "status" must be a string');
        Assert::inArray(
            $payload['status'],
            ['available', 'pending', 'sold'],
            'Status must be one of: available, pending, sold'
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function validateUpdatedPetPayload(Request $request): void
    {
        $payload = $request->toArray();

        if (array_key_exists('category', $payload)) {
            Assert::isArray(
                $payload['category'],
                'Field "category" must be an array'
            );
            if (array_key_exists('name', $payload['category'])) {
                Assert::string(
                    $payload['category']['name'],
                    'Category name must be a string'
                );
            }
        }

        if (array_key_exists('name', $payload)) {
            Assert::string(
                $payload['name'],
                'Field "name" must be a string'
            );
        }

        if (array_key_exists('photoUrls', $payload)) {
            Assert::isArray(
                $payload['photoUrls'],
                'Field "photoUrls" must be an array'
            );
            Assert::allString(
                $payload['photoUrls'],
                'All values in "photoUrls" must be valid URLs (strings)'
            );
        }

        if (array_key_exists('tags', $payload)) {
            Assert::isArray(
                $payload['tags'],
                'Field "tags" must be an array'
            );
            foreach ($payload['tags'] as $index => $tag) {
                Assert::isArray(
                    $tag,
                    sprintf('Tag at position %d must be an object', $index)
                );
                if (array_key_exists('name', $tag)) {
                    Assert::string(
                        $tag['name'],
                        sprintf('Tag name at position %d must be a string', $index)
                    );
                }
            }
        }

        if (array_key_exists('status', $payload)) {
            Assert::string(
                $payload['status'],
                'Field "status" must be a string'
            );
            Assert::inArray(
                $payload['status'],
                ['available', 'pending', 'sold'],
                'Status must be one of: available, pending, sold'
            );
        }
    }
}
