@extends('layouts.app')

@section('content')
    <h2>Lista zwierząt</h2>

    <div class="row mb-4">
        <div class="col-md-6">
            <form action="{{ route('pets.index') }}" method="GET" class="form-inline">
                <div class="input-group">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        @foreach($availableStatuses as $status)
                            <option value="{{ $status }}" {{ $selectedStatus == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>
    @if(count($pets) > 0)
        <div class="row">
            @foreach($pets as $pet)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $pet['name'] ?? 'Brak nazwy' }}</h5>
                            <p class="card-text">
                                Status: <b>{{ $pet['status'] ?? 'Nieznany' }}</b>
                           <br>
                                Kategoria: {{ $pet['category']['name'] ?? '-' }}
                            </p>
                            <div class="d-flex gap-2">
                                <a href="{{ route('pets.edit', $pet['id']) }}" class="btn btn-primary btn-sm">Edytuj</a>
                                <form action="{{ route('pets.destroy', $pet['id']) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Usuń</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info">Brak zwierząt o wybranym statusie.</div>
    @endif
@endsection
