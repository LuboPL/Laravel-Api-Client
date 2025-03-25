<?php
declare(strict_types=1);

namespace App\Actions;

use App\Config\ConfigInterface;
use Illuminate\Http\Request;

abstract class AbstractRequestAction implements RequestActionInterface
{
    public function __construct(protected readonly ConfigInterface $config)
    {
    }

    abstract protected function getRouteName(): string;
    abstract protected function getMethod(): string;

    public function matches(Request $request): bool
    {
        return $request->route()->getName() === $this->getRouteName() &&
            $request->method() === $this->getMethod();
    }
}
