@extends('layouts.app')

@section('content')
    <h2>{{ isset($pet) ? 'Edytuj zwierzę' : 'Dodaj nowe zwierzę' }}</h2>

    <form action="{{ isset($pet) ? route('pets.update', $pet->id) : route('pets.store') }}" method="POST">
        @csrf
        @if(isset($pet)) @method('PUT') @endif

        @if(isset($pet))
            <input type="hidden" name="id" value="{{ $pet->id }}">
            <input type="hidden" name="category[id]" value="{{ $pet->category->id }}">

        @endif

        <div class="mb-3">
            <label class="form-label">Nazwa kategorii</label>
            <input type="text" name="category[name]" class="form-control"
                   value="{{ $pet->category->name ?? '' }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Imię zwierzaka</label>
            <input type="text" name="name" class="form-control"
                   value="{{ $pet->name ?? '' }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Adresy zdjęć</label>
            <div id="photoUrlsContainer">
                @foreach(($pet->photoUrls ?? ['']) as $url)
                    <input type="url" name="photoUrls[]" class="form-control mb-2"
                           value="{{ $url }}" required>
                @endforeach
            </div>
            <button type="button" class="btn btn-secondary btn-sm" onclick="addPhotoUrlField()">Dodaj zdjęcie</button>
        </div>

        <div class="mb-3">
            <label class="form-label">Tagi</label>
            <div id="tagsContainer">
                @foreach(($pet->tags ?? [new App\Models\Tag(id: 0, name: '')]) as $index => $tag)
                    <div class="tag-input mb-2">
                        <input type="hidden" name="tags[{{ $index }}][id]" value="{{ data_get($tag, 'id', 0) }}">
                        <input type="text" name="tags[{{ $index }}][name]" class="form-control"
                               value="{{ data_get($tag, 'name', '') }}" placeholder="Nazwa tagu">
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-secondary btn-sm" onclick="addTagField()">Dodaj tag</button>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-control" required>
                <option value="available" {{ (isset($pet) && $pet->status == 'available') ? 'selected' : '' }}>Dostępny</option>
                <option value="pending" {{ (isset($pet) && $pet->status == 'pending') ? 'selected' : '' }}>Oczekujący</option>
                <option value="sold" {{ (isset($pet) && $pet->status == 'sold') ? 'selected' : '' }}>Sprzedany</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($pet) ? 'Zaktualizuj' : 'Dodaj' }}</button>
    </form>

    <script>
        function addPhotoUrlField() {
            const container = document.getElementById('photoUrlsContainer');
            const newInput = document.createElement('input');
            newInput.type = 'url';
            newInput.name = 'photoUrls[]';
            newInput.className = 'form-control mb-2';
            newInput.placeholder = 'https://example.com/photo.jpg';
            container.appendChild(newInput);
        }

        function addTagField() {
            const container = document.getElementById('tagsContainer');
            const newInput = document.createElement('input');
            newInput.type = 'text';
            newInput.name = `tags[${container.children.length}][name]`;
            newInput.className = 'form-control mb-2';
            newInput.placeholder = 'Nazwa tagu';
            container.appendChild(newInput);
        }
    </script>
@endsection
