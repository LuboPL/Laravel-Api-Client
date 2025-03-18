<?php
declare(strict_types=1);

namespace App\Service\Factory;

use App\Http\Requests\RequestInterface;
use Illuminate\Http\Request;

interface RequestFactoryInterface
{
    public function create(Request $request): RequestInterface;
}
