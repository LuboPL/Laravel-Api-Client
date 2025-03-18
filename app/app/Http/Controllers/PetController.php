<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Config\ConfigInterface;
use App\Http\Requests\PetStoreRequest;
use App\Service\PayloadMapper;
use App\Validator\PayloadValidator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Webmozart\Assert\InvalidArgumentException;

class PetController extends Controller
{
    public function __construct(
        private readonly PayloadMapper $payloadMapper,
        private readonly PayloadValidator $payloadValidator,
        private readonly PetStoreRequest $petStoreRequest,
        private readonly ConfigInterface $config
    )
    {
    }

    public function index(Request $request): View|RedirectResponse
    {
        $selectedStatus = $request->input('status', 'available');
        try {
            $response = $this->petStoreRequest->getPetsByStatus($selectedStatus);
            $pets = $this->payloadMapper->mapPetsFromResponse($response);

            return view('pets.index', [
                'pets' => $pets,
                'availableStatuses' => $this->config->getAvailableStatuses(),
                'selectedStatus' => $selectedStatus
            ]);
        } catch (\Throwable $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function create(): View|Factory
    {
        return view('pets.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $payload = $request->toArray();

        try {
            $this->payloadValidator->validatePetPayload($request);
            $pet = $this->payloadMapper->mapPetFromArray($payload);
            $response = $this->petStoreRequest->postNewPet($pet);

            if (false === $response->successful()) {
                return redirect()->back()->with('error', 'Błąd API: ' . $response->body());
            }
            return redirect()->back()->with('success', 'Zwierzak został dodany!');

        } catch (InvalidArgumentException|\Throwable $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function edit($id): mixed
    {
        $response = $this->petStoreRequest->getPetById((int)$id);

        if ($response->successful()) {
            return view('pets.create', ['pet' => $this->payloadMapper->mapPetFromArray($response->json())]);
        }

        return redirect()->route('pets.index')->with('error', 'Nie znaleziono zwierzęcia');
    }

    public function update(Request $request): RedirectResponse
    {
        $pet = $this->payloadMapper->mapPetFromArray($request->array());

        $response = $this->petStoreRequest->putPet($pet);

        if ($response->successful()) {
            return redirect()->route('pets.index')->with('success', 'Zaktualizowano pomyślnie!');
        }

        return back()->with('error', 'Błąd aktualizacji: ' . $response->body());
    }

    public function destroy($id): RedirectResponse
    {
        $response = Http::delete($this->apiUrl . '/' . $id);

        if ($response->successful()) {
            return redirect()->route('pets.index')->with('success', 'Usunięto pomyślnie!');
        }

        return back()->with('error', 'Błąd usuwania: ' . $response->body());
    }

    private function prepareData(Request $request): array
    {
        return [
            'id' => $request->input('id'),
            'category' => [
                'name' => $request->input('category.name')
            ],
            'name' => $request->input('name'),
            'photoUrls' => $request->input('photoUrls'),
            'tags' => array_filter(array_map(function($tag) {
                return ['name' => $tag['name']];
            }, $request->input('tags') ?? [])),
            'status' => $request->input('status')
        ];
    }
}
