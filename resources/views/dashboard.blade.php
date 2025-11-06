@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Panel de control')

@section('content')
<div style="display:flex;align-items:center;justify-content:center;min-height:60vh;">
	<div class="card" style="max-width:720px;width:100%;">
		<h3 style="margin:0 0 6px 0;text-align:center">Bienvenido, {{ $user->name ?? $user->email }}</h3>
		<p style="color:var(--muted);margin:0 0 12px 0;text-align:center">Has iniciado sesión como <strong>{{ $user->email }}</strong></p>

		<p style="text-align:center">Este es el dashboard básico. Aquí construiremos las secciones que necesites.</p>

		<p style="margin-top:18px;text-align:center;">
			<!-- El logout ya lo maneja el topbar; puedes mantener acciones aquí si las prefieres -->
			<a href="{{ route('dashboard') }}" style="text-decoration:none;color:var(--accent)">Ir a refrescar</a>
		</p>
	</div>
</div>
@endsection
