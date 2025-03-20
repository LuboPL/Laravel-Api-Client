<?php
declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\Registry\RequestActionRegistry;
use App\Actions\RequestActionInterface;
use App\Config\ConfigInterface;
use App\Http\Controllers\PetController;
use App\Service\PetPayloadMapper;
use App\Validator\PetPayloadValidator;
use Illuminate\Http\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Mockery;
use Tests\TestCase;
use Webmozart\Assert\InvalidArgumentException;

class PetControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testStoreSuccessfullyAddsPet(): void
    {
        $request = Mockery::mock(Request::class);

        $response = Mockery::mock(Response::class);
        $response->shouldReceive('successful')->andReturn(true);

        $requestInterface = Mockery::mock(\App\Actions\Requests\RequestInterface::class);
        $requestInterface->shouldReceive('create')->andReturn($response);

        $requestAction = Mockery::mock(RequestActionInterface::class);
        $requestAction->shouldReceive('createRequest')
            ->with($request)
            ->andReturn($requestInterface);

        $registry = Mockery::mock(RequestActionRegistry::class);
        $registry->shouldReceive('findAction')
            ->with($request)
            ->andReturn($requestAction);

        $validator = Mockery::mock(PetPayloadValidator::class);
        $validator->shouldReceive('validateNewPetPayload')
            ->with($request)
            ->andReturn(true);

        $mapper = Mockery::mock(PetPayloadMapper::class);
        $config = Mockery::mock(ConfigInterface::class);

        $controller = new PetController(
            $mapper,
            $validator,
            $registry,
            $config
        );

        $redirectResponse = Mockery::mock(RedirectResponse::class);
        $redirectResponse->shouldReceive('with')
            ->with('success', 'Pet added successfully!')
            ->andReturnSelf();

        $redirector = Mockery::mock(\Illuminate\Routing\Redirector::class);
        $redirector->shouldReceive('back')->andReturn($redirectResponse);

        $this->app->instance('redirect', $redirector);

        $result = $controller->store($request);

        $this->assertSame($redirectResponse, $result);
    }

    public function testStoreWithValidationErrorRedirectsWithError(): void
    {
        $request = Mockery::mock(Request::class);

        $validator = Mockery::mock(PetPayloadValidator::class);
        $validator->shouldReceive('validateNewPetPayload')
            ->with($request)
            ->andThrow(new InvalidArgumentException('Validation error'));

        $mapper = Mockery::mock(PetPayloadMapper::class);
        $registry = Mockery::mock(RequestActionRegistry::class);
        $config = Mockery::mock(ConfigInterface::class);

        $controller = new PetController(
            $mapper,
            $validator,
            $registry,
            $config
        );

        $redirectResponse = Mockery::mock(RedirectResponse::class);
        $redirectResponse->shouldReceive('with')
            ->with('error', 'Validation error')
            ->andReturn($redirectResponse);

        $redirector = Mockery::mock(Redirector::class);
        $redirector->shouldReceive('back')
            ->andReturn($redirectResponse);

        $this->app->instance('redirect', $redirector);

        $result = $controller->store($request);
        $this->assertSame($redirectResponse, $result);
    }

    public function testUpdateSuccessfullyUpdatesPet(): void
    {
        $request = Mockery::mock(Request::class);

        $response = Mockery::mock(Response::class);
        $response->shouldReceive('successful')->andReturn(true);

        $requestInterface = Mockery::mock(\App\Actions\Requests\RequestInterface::class);
        $requestInterface->shouldReceive('create')->andReturn($response);

        $requestAction = Mockery::mock(RequestActionInterface::class);
        $requestAction->shouldReceive('createRequest')
            ->with($request)
            ->andReturn($requestInterface);

        $registry = Mockery::mock(RequestActionRegistry::class);
        $registry->shouldReceive('findAction')
            ->with($request)
            ->andReturn($requestAction);

        $mapper = Mockery::mock(PetPayloadMapper::class);
        $validator = Mockery::mock(PetPayloadValidator::class);
        $config = Mockery::mock(ConfigInterface::class);

        $controller = new PetController(
            $mapper,
            $validator,
            $registry,
            $config
        );

        $redirectResponse = Mockery::mock(RedirectResponse::class);
        $redirectResponse->shouldReceive('with')
            ->with('success', 'Pet updated successfully!')
            ->andReturn($redirectResponse);

        $redirector = Mockery::mock(\Illuminate\Routing\Redirector::class);
        $redirector->shouldReceive('route')
            ->with('pets.index')
            ->andReturn($redirectResponse);

        $this->app->instance('redirect', $redirector);

        $result = $controller->update($request);
        $this->assertSame($redirectResponse, $result);
    }


    public function testDestroySuccessfullyDeletesPet(): void
    {
        $request = Mockery::mock(Request::class);

        $response = Mockery::mock(Response::class);
        $response->shouldReceive('successful')->andReturn(true);

        $requestInterface = Mockery::mock(\App\Actions\Requests\RequestInterface::class);
        $requestInterface->shouldReceive('create')->andReturn($response);

        $requestAction = Mockery::mock(RequestActionInterface::class);
        $requestAction->shouldReceive('createRequest')
            ->with($request)
            ->andReturn($requestInterface);

        $registry = Mockery::mock(RequestActionRegistry::class);
        $registry->shouldReceive('findAction')
            ->with($request)
            ->andReturn($requestAction);

        $mapper = Mockery::mock(PetPayloadMapper::class);
        $validator = Mockery::mock(PetPayloadValidator::class);
        $config = Mockery::mock(ConfigInterface::class);

        $controller = new PetController(
            $mapper,
            $validator,
            $registry,
            $config
        );

        $redirectResponse = Mockery::mock(RedirectResponse::class);
        $redirectResponse->shouldReceive('with')
            ->with('success', 'Pet deleted successfully!')
            ->andReturn($redirectResponse);

        $redirector = Mockery::mock(\Illuminate\Routing\Redirector::class);
        $redirector->shouldReceive('route')
            ->with('pets.index')
            ->andReturn($redirectResponse);

        $this->app->instance('redirect', $redirector);

        $result = $controller->destroy($request);
        $this->assertSame($redirectResponse, $result);
    }
}
