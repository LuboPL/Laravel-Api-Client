@extends('layouts.app')

@section('content')
    <h2>{{ isset($pet) ? 'Edytuj zwierzę' : 'Dodaj nowe zwierzę' }}</h2>

    <form action="{{ isset($pet) ? route('pets.update', $pet->id) : route('pets.store') }}" method="POST">
        @csrf
        @if(isset($pet))
            @method('PUT')
        @endif

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

    @if(isset($pet))
        <div class="mt-4">
            <h4>Prześlij nowe zdjęcie</h4>
            <form action="{{ route('pets.uploadImage', ['petId' => $pet->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="input-group">
                    <input type="file" name="pet_image" class="form-control" accept="image/*">
                    <input type="text" name="additionalMetadata" class="form-control" placeholder="Dodatkowe informacje">
                    <button type="submit" class="btn btn-secondary">Prześlij zdjęcie</button>
                </div>
            </form>
        </div>
    @endif

    @if(isset($pet))
        <div class="mt-4">
            <h4>Aktualizacja podstawowych danych</h4>
            <form action="{{ route('pets.updateWithForm', ['petId' => $pet->id]) }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Imię zwierzaka</label>
                        <input type="text" name="name" class="form-control" value="{{ $pet->name ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="available" {{ $pet->status == 'available' ? 'selected' : '' }}>Dostępny</option>
                            <option value="pending" {{ $pet->status == 'pending' ? 'selected' : '' }}>Oczekujący</option>
                            <option value="sold" {{ $pet->status == 'sold' ? 'selected' : '' }}>Sprzedany</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-info">Zaktualizuj</button>
                    </div>
                </div>
            </form>
        </div>
    @endif

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
            const div = document.createElement('div');
            div.className = 'tag-input mb-2';

            const index = container.children.length;

            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = `tags[${index}][id]`;
            hiddenInput.value = '0';

            const textInput = document.createElement('input');
            textInput.type = 'text';
            textInput.name = `tags[${index}][name]`;
            textInput.className = 'form-control';
            textInput.placeholder = 'Nazwa tagu';

            div.appendChild(hiddenInput);
            div.appendChild(textInput);
            container.appendChild(div);
        }
    </script>
@endsection
