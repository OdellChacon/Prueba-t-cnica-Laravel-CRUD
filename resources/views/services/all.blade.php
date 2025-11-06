@extends('layouts.app')
@section('title','Servicios')
@section('subtitle', isset($provider) ? $provider->name : 'Servicios')
@section('content')

<div class="card" style="background:#fff;">

@php
    $storeRouteObj = Route::getRoutes()->getByName('services.store') ?? null;
    $serviceStoreAllowsPost = $storeRouteObj ? in_array('POST', $storeRouteObj->methods()) : false;
@endphp

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">

	<div style="display:flex;align-items:center;gap:10px;">
		{{-- Un único botón que abre el modal de creación de servicios --}}
		<button type="button" id="btnOpenCreateService" class="btn-new-service" data-action-template="{{ url('providers/::id::/services') }}" aria-label="Nuevo servicio" style="display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border-radius:10px;background:linear-gradient(90deg,#10b981,#059669);color:#fff;font-weight:700;border:0;cursor:pointer;box-shadow:0 8px 20px rgba(5,150,105,0.12)">
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
			<span>Nuevo servicio</span>
		</button>

		<a href="{{ route('services.trash') }}" style="display:inline-flex;align-items:center;gap:8px;padding:8px 10px;border-radius:10px;background:transparent;color:#ef4444;text-decoration:none;font-weight:600;border:1px solid rgba(239,68,68,0.08)">
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
			<span>Ver eliminados</span>
		</a>
	</div>	

<!-- buscador + contador (como en providers.index) -->
	<div style="display:flex;align-items:center;gap:8px;">
		<form method="GET" action="{{ route('services.index') }}" style="display:flex;align-items:center;gap:8px;">
			<div style="display:flex;align-items:center;background:#fff;padding:6px 8px;border-radius:10px;border:1px solid #e6edf3;box-shadow:0 6px 18px rgba(2,6,23,0.04);">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;margin-right:6px;flex-shrink:0"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
				<input id="searchInput" type="search" name="q" value="{{ request('q') }}" placeholder="Buscar servicios..." title="Buscar servicios" aria-label="Buscar servicios" autocomplete="off" style="border:0;outline:none;padding:8px 6px;font-size:14px;min-width:180px;background:transparent;box-sizing:border-box">
			</div>
			<button type="submit" aria-label="Buscar" style="display:inline-flex;align-items:center;justify-content:center;width:40px;height:40px;border-radius:10px;background:#3b82f6;color:#fff;border:0;cursor:pointer;box-shadow:0 8px 18px rgba(59,130,246,0.12)">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
			</button>
		</form>

		<div style="color:#6b7280;font-size:13px">Mostrando {{ $services->count() }} de {{ $services->total() }}</div>
	</div>

</div>

@if(session('success'))
	<div style="color:green;margin-bottom:8px">{{ session('success') }}</div>
@endif

<table style="width:100%;border-collapse:collapse;text-align:center">
     <thead>
        <tr style="text-align:center;border-bottom:1px solid #e6edf3">
            <th style="padding:8px;">Nombre</th>
            <th style="padding:8px;">Duración (min)</th>
            <th style="padding:8px;">Precio</th>
            <th style="padding:8px;">Proveedor</th> <!-- nueva columna -->
            <th style="padding:8px;"></th>
        </tr>
     </thead>
     <tbody>
     @foreach($services as $s)
         <tr style="border-bottom:1px solid #f1f5f9"
             data-name="{{ $s->name }}"
             data-duration="{{ $s->duration_minutes }}"
             data-price="{{ $s->price }}">
             <td style="padding:8px;vertical-align:middle;text-align:center">{{ $s->name }}</td>
             <td style="padding:8px;vertical-align:middle;text-align:center">{{ $s->duration_minutes }}</td>
             <td style="padding:8px;vertical-align:middle;text-align:center">{{ '$' . number_format($s->price, 2) }}</td>
             <td style="padding:8px;vertical-align:middle;text-align:center">{{ optional($s->provider)->name ?? '—' }}</td> <!-- proveedor -->
             <td style="padding:8px;vertical-align:middle;text-align:center">
                <!-- editar: botón parecido al de providers -->
                <button type="button"
                    class="btn-edit"
                    data-action="{{ route('services.update', $s) }}"
                    data-id="{{ $s->id }}"
                    data-name="{{ $s->name }}"
                    data-duration="{{ $s->duration_minutes }}"
                    data-price="{{ $s->price }}"
                    aria-label="Editar {{ $s->name }}"
                    style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:8px;background:#f59e0b;color:#fff;border:0;cursor:pointer;font-weight:600;margin-right:8px">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" role="img" aria-hidden="true" style="width:16px;height:16px">
                        <path d="M16.5 3.5l4 4L7 21l-4 1 1-4L16.5 3.5z"/>
                    </svg>
                </button>

                <!-- eliminar: ahora abre modal de confirmación en lugar de enviar el form inmediatamente -->
                <button type="button"
                    class="btn-delete"
                    data-action="{{ route('services.destroy', $s) }}"
                    data-name="{{ $s->name }}"
                    aria-label="Eliminar {{ $s->name }}"
                    style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:8px;background:#ef4444;color:#fff;border:0;cursor:pointer;font-weight:600;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" role="img" aria-hidden="true" style="width:14px;height:14px">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                            <path d="M10 11v6"/>
                            <path d="M14 11v6"/>
                        </svg>
                </button>
            </td>
         </tr>
     @endforeach
     </tbody>
 </table>

<div style="margin-top:12px;display:flex;justify-content:center;">
	@php
		$p = $services;
		$current = $p->currentPage();
		$last = $p->lastPage();
		$start = max(1, $current - 2);
		$end = min($last, $current + 2);
	@endphp

	@if($last > 1)
		<nav aria-label="Paginación de servicios" style="display:flex;align-items:center;gap:10px;font-size:13px;color:#374151">
			<div style="color:#6b7280">Mostrando {{ $p->perPage() }} por página · Total: {{ $p->total() }}</div>
			<ul style="list-style:none;display:flex;gap:6px;margin:0;padding:0;align-items:center">
				<li>
					@if($p->onFirstPage())
						<span aria-hidden="true" style="display:inline-flex;align-items:center;gap:6px;padding:8px 10px;border-radius:8px;background:#f3f4f6;color:#9ca3af">Prev</span>
					@else
						<a href="{{ $p->previousPageUrl() }}" rel="prev" style="display:inline-flex;align-items:center;gap:6px;padding:8px 10px;border-radius:8px;background:#fff;border:1px solid #e6edf3;color:#374151;text-decoration:none">Prev</a>
					@endif
				</li>

				<li>
					@if($current == 1)
						<span aria-current="page" style="padding:8px 10px;border-radius:8px;background:#2563eb;color:#fff;font-weight:600">1</span>
					@else
						<a href="{{ $p->url(1) }}" style="padding:8px 10px;border-radius:8px;background:#fff;border:1px solid #e6edf3;color:#374151;text-decoration:none">1</a>
					@endif
				</li>

				@if($start > 2)<li><span style="padding:8px 6px;color:#9ca3af">…</span></li>@endif

				@for($i = $start; $i <= $end; $i++)
					@if($i == 1 || $i == $last) @continue @endif
					<li>
						@if($i == $current)
							<span aria-current="page" style="padding:8px 10px;border-radius:8px;background:#2563eb;color:#fff;font-weight:600">{{ $i }}</span>
						@else
							<a href="{{ $p->url($i) }}" style="padding:8px 10px;border-radius:8px;background:#fff;border:1px solid #e6edf3;color:#374151;text-decoration:none">{{ $i }}</a>
						@endif
					</li>
				@endfor

				@if($end < $last - 1)<li><span style="padding:8px 6px;color:#9ca3af">…</span></li>@endif

				@if($last > 1)
					<li>
						@if($current == $last)
							<span aria-current="page" style="padding:8px 10px;border-radius:8px;background:#2563eb;color:#fff;font-weight:600">{{ $last }}</span>
						@else
							<a href="{{ $p->url($last) }}" style="padding:8px 10px;border-radius:8px;background:#fff;border:1px solid #e6edf3;color:#374151;text-decoration:none">{{ $last }}</a>
						@endif
					</li>
				@endif

				<li>
					@if($p->hasMorePages())
						<a href="{{ $p->nextPageUrl() }}" rel="next" style="display:inline-flex;align-items:center;gap:6px;padding:8px 10px;border-radius:8px;background:#2563eb;color:#fff;text-decoration:none;font-weight:600">Siguiente</a>
					@else
						<span aria-hidden="true" style="display:inline-flex;align-items:center;gap:6px;padding:8px 10px;border-radius:8px;background:#f3f4f6;color:#9ca3af">Siguiente</span>
					@endif
				</li>
			</ul>
		</nav>
	@endif
</div>

{{-- Incluimos fragmentos existentes --}}
@include('services.create_fragment', ['serviceStoreAllowsPost' => $serviceStoreAllowsPost, 'provider' => $provider ?? null])
@include('services.search_script')
@include('services.edit') {{-- modal edit services --}}

{{-- Modal de confirmación para eliminar servicios --}}
<div id="deleteServiceModal" style="display:none;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;background:rgba(0,0,0,0.45);">
	<div role="dialog" aria-modal="true" aria-labelledby="deleteServiceTitle" style="background:#fff;padding:16px;border-radius:10px;max-width:480px;width:92%;box-shadow:0 6px 18px rgba(0,0,0,0.12)">
		<h3 id="deleteServiceTitle" style="margin:0 0 8px 0;font-size:18px">Confirmar eliminación</h3>
		<p id="deleteServiceText" style="margin:0;color:#374151">¿Eliminar este servicio?</p>

		<div style="display:flex;justify-content:flex-end;gap:8px;margin-top:16px">
			<button id="cancelDeleteService" type="button" style="padding:8px 12px;border-radius:8px;background:#e5e7eb;border:0;cursor:pointer">Cancelar</button>
			<button id="confirmDeleteService" type="button" style="padding:8px 12px;border-radius:8px;background:#ef4444;color:#fff;border:0;cursor:pointer">Eliminar</button>
		</div>
	</div>
</div>

<!-- formulario oculto único para envío DELETE de servicios -->
<form id="deleteServiceForm" method="POST" style="display:none;">
	@csrf
	@method('DELETE')
</form>

<script>
	(function(){
		const modal = document.getElementById('deleteServiceModal');
		const textEl = document.getElementById('deleteServiceText');
		const btnCancel = document.getElementById('cancelDeleteService');
		const btnConfirm = document.getElementById('confirmDeleteService');
		const hiddenForm = document.getElementById('deleteServiceForm');

		let currentAction = null;

		// Delegación: manejar clicks en cualquier .btn-delete, incluso si se añadieron dinámicamente
		document.addEventListener('click', function(e){
			const btn = e.target.closest && e.target.closest('.btn-delete');
			if (!btn) return;

			// evitar manejar clicks si el botón es parte de otro formulario/link etc.
			e.preventDefault();

			currentAction = btn.getAttribute('data-action');
			const name = btn.getAttribute('data-name') || 'este servicio';
			if (textEl) textEl.textContent = '¿Eliminar el servicio "' + name + '"? Esta acción no se puede deshacer.';
			if (modal) modal.style.display = 'flex';
		});

		if (btnCancel) btnCancel.addEventListener('click', function(){ if (modal) modal.style.display = 'none'; currentAction = null; });

		if (btnConfirm) btnConfirm.addEventListener('click', function(){
			if (!currentAction) return;
			if (hiddenForm) {
				hiddenForm.setAttribute('action', currentAction);
				hiddenForm.submit();
			} else {
				// fallback: enviar mediante fetch con _method=DELETE (intentar respetar CSRF token)
				const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
				fetch(currentAction, {
					method: 'POST',
					headers: { 'X-CSRF-TOKEN': token, 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded' },
					body: new URLSearchParams({ '_method': 'DELETE' })
				}).then(()=> window.location.reload()).catch(()=> window.location.href = currentAction);
			}
		});

		if (modal) modal.addEventListener('click', function(e){ if (e.target === modal) { modal.style.display = 'none'; currentAction = null; } });
	})();
</script>

</div>
@endsection
