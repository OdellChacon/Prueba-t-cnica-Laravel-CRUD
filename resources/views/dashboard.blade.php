@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Panel de control')

@section('content')
<div style="display:flex;align-items:center;justify-content:center;min-height:60vh;padding:24px;">
	<div style="max-width:720px;width:100%;text-align:center;border-radius:12px;padding:28px;background:#fff;box-shadow:0 8px 30px rgba(0,0,0,0.06);">
		<h3 style="margin:0 0 8px 0;font-size:20px;">Bienvenido, {{ $user->name ?? $user->email }}</h3>
		<p style="margin:0 0 16px 0;color:var(--muted);">Has iniciado sesión como <strong>{{ $user->email }}</strong></p>

		<div style="display:inline-block;padding:10px 14px;border-radius:999px;background:#ffedd5;color:#b45309;font-weight:700;margin-bottom:8px;">
			En construcción
		</div>

		<p style="margin:12px 0 0 0;color:var(--muted);">Estamos preparando esta sección. Vuelve pronto.</p>
	</div>
</div>
@endsection
