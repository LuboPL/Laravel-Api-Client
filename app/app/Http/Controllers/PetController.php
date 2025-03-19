<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Action\Registry\RequestActionRegistry;
use App\Config\ConfigInterface;
use App\Service\PetPayloadMapper;
use App\Validator\PetPayloadValidator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Webmozart\Assert\InvalidArgumentException;

class PetController extends Controller
{
    public function __construct(
        private readonly PetPayloadMapper      $payloadMapper,
        private readonly PetPayloadValidator   $payloadValidator,
        private readonly RequestActionRegistry $requestActionRegistry,
        private readonly ConfigInterface       $config
    )
    {
    }

    public function index(Request $request): View|RedirectResponse
    {
        try {
            $action = $this->requestActionRegistry->findAction($request);
            $request = $action->createRequest($request);
            $response = $request->create();
            $pets = $this->payloadMapper->mapPetsFromResponse($response);

            return view('pets.index', [
                'pets' => $pets,
                'availableStatuses' => $this->config->getAvailableStatuses(),
                'selectedStatus' => $request->getStatus()
            ]);
        } catch (\Throwable $exception) {
            return redirect()
                ->back()
                ->with('error', $exception->getMessage());
        }
    }

    public function create(): View|Factory
    {
        return view('pets.create');
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $this->payloadValidator->validateNewPetPayload($request);
            $action = $this->requestActionRegistry->findAction($request);
            $request = $action->createRequest($request);
            $response = $request->create();

            if (false === $response->successful()) {
                return redirect()
                    ->back()
                    ->with('error', $response->body());
            }
            return redirect()
                ->back()
                ->with('success', 'Pet added successfully!');
        } catch (InvalidArgumentException|\Throwable $exception) {
            return redirect()
                ->back()
                ->with('error', $exception->getMessage());
        }
    }

    public function edit(Request $request): mixed
    {
        try {
            $action = $this->requestActionRegistry->findAction($request);
            $request = $action->createRequest($request);
            $response = $request->create();
            if ($response->successful()) {
                return view(
                    'pets.create',
                    [
                        'pet' => $this->payloadMapper->mapPetFromArray($response->json())
                    ]
                );
            }

        } catch (InvalidArgumentException|\Throwable $exception) {
            return redirect()
                ->back()
                ->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('pets.index')
            ->with('error', 'Not found pet');
    }

    public function update(Request $request): RedirectResponse
    {
        try {
            $action = $this->requestActionRegistry->findAction($request);
            $request = $action->createRequest($request);
            $response = $request->create();

            if ($response->successful()) {
                return redirect()
                    ->route('pets.index')
                    ->with('success', 'Pet updated successfully!');
            }

            return back()
                ->with('error', $response->body());

        } catch (InvalidArgumentException|\Throwable $exception) {
            return back()
                ->with('error', $exception->getMessage());
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        try {
            $action = $this->requestActionRegistry->findAction($request);
            $request = $action->createRequest($request);
            $response = $request->create();
            if ($response->successful()) {
                return redirect()
                    ->route('pets.index')
                    ->with('success', 'Pet deleted successfully!');
            }
        } catch (InvalidArgumentException|\Throwable $exception) {
            return back()
                ->with('error', $exception->getMessage());
        }

        return back()
            ->with('error', $response->body());
    }
}
