<?php
declare(strict_types=1);

namespace App\Validator;

use Illuminate\Http\Request;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

class PayloadValidator
{
    /**
     * @throws InvalidArgumentException
     */
    public function validatePetPayload(Request $request): void
    {
        $payload = $request->toArray();

        Assert::keyExists($payload, 'category');
        Assert::string($payload['category']);
        Assert::notEmpty($payload['category']);

        Assert::keyExists($payload, 'name');
        Assert::string($payload['name']);
        Assert::notEmpty($payload['name']);

        Assert::isArray($payload['photoUrls']);
        Assert::allString($payload['photoUrls']);

        Assert::isArray($payload['tags']);
        foreach ($payload['tags'] as $tag) {
            Assert::isArray($tag);
            Assert::string($tag['name']);
        }

        Assert::keyExists($payload, 'status');
        Assert::string($payload['status']);
        Assert::inArray($payload['status'], ['available', 'pending', 'sold']);
    }
}
