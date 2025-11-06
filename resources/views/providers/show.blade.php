@extends('layouts.app')

@section('title',$provider->name)
@section('subtitle','Proveedor')

@section('content')
<div class="card">
	<h3>{{ $provider->name }}</h3>
	<p>{{ $provider->email }} · {{ $provider->phone }}</p>

	<hr>
	<p><a href="{{ route('providers.services.index',$provider) }}">Ver servicios ({{ $provider->services->count() }})</a></p>

	<p><a href="{{ route('providers.index') }}">Volver</a></p>
</div>
@endsection
