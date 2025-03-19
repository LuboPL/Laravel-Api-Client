<?php
declare(strict_types=1);

namespace App\Actions;

use App\Actions\Requests\RequestInterface;
use Illuminate\Http\Request;

interface RequestActionInterface
{
    public function matches(Request $request): bool;
    public function createRequest(Request $request): RequestInterface;
}
