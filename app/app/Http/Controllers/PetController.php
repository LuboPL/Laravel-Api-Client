<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class PetController extends Controller
{
    private string $apiUrl = 'https://petstore.swagger.io/v2/pet';

    public function index(Request $request): View|Factory
    {
        $selectedStatus = $request->input('status', 'available');

        $response = Http::get($this->apiUrl . '/findByStatus', [
            'status' => $selectedStatus
        ]);

        $pets = $response->successful() ? $response->json() : [];

        return view('pets.index', [
            'pets' => $pets,
            'availableStatuses' => ['available', 'pending', 'sold'],
            'selectedStatus' => $selectedStatus
        ]);
    }

    public function create(): View|Factory
    {
        return view('pets.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'category.name' => 'required|string',
            'name' => 'required|string',
            'photoUrls.*' => 'nullable|url',
            'tags.*.name' => 'nullable|string',
            'status' => 'required|in:available,pending,sold',
        ]);

        $data = [
            'category' => [
                'name' => $request->input('category.name'),
            ],
            'name' => $request->input('name'),
            'photoUrls' => $request->input('photoUrls'),
            'tags' => collect($request->input('tags'))->filter(function ($tag) {
                return !empty($tag['name']);
            })->values()->toArray(),
            'status' => $request->input('status'),
        ];

        try {
            $response = Http::post($this->apiUrl, $data);

            if ($response->successful()) {
                return redirect()->back()->with('success', 'Zwierzak został dodany!');
            } else {
                return redirect()->back()->with('error', 'Błąd API: ' . $response->body());
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Błąd połączenia: ' . $e->getMessage());
        }
    }

    public function edit($id): mixed
    {
        $response = Http::get($this->apiUrl . '/' . $id);

        if ($response->successful()) {
            return view('pets.create', ['pet' => $response->json()]);
        }

        return redirect()->route('pets.index')->with('error', 'Nie znaleziono zwierzęcia');
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $this->prepareData($request);

        $response = Http::put($this->apiUrl, $data);

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
