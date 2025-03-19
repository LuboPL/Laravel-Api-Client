<?php
declare(strict_types=1);

namespace App\Action;

use App\Config\ConfigInterface;
use Illuminate\Http\Request;

abstract class AbstractRequestAction implements RequestActionInterface
{
    public function __construct(protected readonly ConfigInterface $config)
    {
    }

    abstract protected function getAppUri(): string;
    abstract protected function getMethod(): string;

    public function matches(Request $request): bool
    {
        return $request->route()->uri() === $this->getAppUri() &&
            $request->method() === $this->getMethod();
    }
}
