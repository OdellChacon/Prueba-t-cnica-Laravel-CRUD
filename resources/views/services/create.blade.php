@extends('layouts.app')
@section('title','Nuevo servicio')
@section('subtitle','Crear servicio')
@section('content')
<h2>Nuevo servicio para {{ $provider->name }}</h2>

@if($errors->any())<div><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

<form action="{{ route('providers.services.store', $provider) }}" method="POST">
    @csrf
    <label>Nombre</label>
    <input type="text" name="name" value="{{ old('name') }}" required>

    <label>Duración (min)</label>
    <input type="number" name="duration_minutes" value="{{ old('duration_minutes', 30) }}" min="1" required>

    <label>Precio</label>
    <input type="number" step="0.01" name="price" value="{{ old('price', '0.00') }}" min="0" required>

    <button type="submit">Crear</button>
    <a href="{{ route('providers.services.index', $provider) }}">Cancelar</a>
</form>

{{-- Incluir fragmento de creación (modal + comportamiento) para centralizar todo lo relacionado a "crear" --}}
@include('services.create_fragment', ['serviceStoreAllowsPost' => (Route::getRoutes()->getByName('services.store') ? in_array('POST', Route::getRoutes()->getByName('services.store')->methods()) : false), 'provider' => $provider ?? null])

@endsection
