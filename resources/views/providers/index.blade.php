@extends('layouts.app')

@section('title','Proveedores')
@section('subtitle','Listado de proveedores')

@section('content')
<div class="card">
	<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
		<div style="display:flex;align-items:center;gap:10px;">
			<button type="button" class="btn-new" data-action="{{ route('providers.store') }}" aria-label="Nuevo proveedor" style="display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border-radius:10px;background:linear-gradient(90deg,#10b981,#059669);color:#fff;font-weight:700;box-shadow:0 8px 20px rgba(5,150,105,0.12);border:0;cursor:pointer">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px">
					<line x1="12" y1="5" x2="12" y2="19"/>
					<line x1="5" y1="12" x2="19" y2="12"/>
				</svg>
				<span style="font-size:14px;line-height:1">Nuevo</span>
			</button>

			<a href="{{ route('providers.trash') }}" aria-label="Ver eliminados" style="display:inline-flex;align-items:center;gap:8px;padding:8px 10px;border-radius:10px;background:transparent;color:#ef4444;text-decoration:none;font-weight:600;border:1px solid rgba(239,68,68,0.08);box-shadow:inset 0 0 0 0 transparent;transition:background .08s">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px">
					<polyline points="3 6 5 6 21 6"/>
					<path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
				</svg>
				<span style="font-size:13px;line-height:1">Eliminados</span>
			</a>
		</div>

		<div>
			<form method="GET" action="{{ route('providers.index') }}" style="display:flex;align-items:center;gap:8px;">
				<div style="display:flex;align-items:center;background:#fff;padding:6px 8px;border-radius:10px;border:1px solid #e6edf3;box-shadow:0 6px 18px rgba(2,6,23,0.04);">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;margin-right:6px;flex-shrink:0">
						<circle cx="11" cy="11" r="7"/>
						<line x1="21" y1="21" x2="16.65" y2="16.65"/>
					</svg>
					<input id="searchInput" type="search" name="q" value="{{ request('q') }}" placeholder="Buscar proveedores..." title="Buscar proveedores" aria-label="Buscar proveedores" autocomplete="off" style="border:0;outline:none;padding:8px 6px;font-size:14px;min-width:160px;background:transparent;box-sizing:border-box">
				</div>
				<button type="submit" aria-label="Buscar" style="display:inline-flex;align-items:center;justify-content:center;width:40px;height:40px;border-radius:10px;background:#3b82f6;color:#fff;border:0;cursor:pointer;box-shadow:0 8px 18px rgba(59,130,246,0.12)">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px">
						<circle cx="11" cy="11" r="7"/>
						<line x1="21" y1="21" x2="16.65" y2="16.65"/>
					</svg>
				</button>
			</form>
		</div>
	</div>

	{{-- Mensajes de éxito y errores de validación (mostrar cuando crear falló) --}}
	@if(session('success'))
		<div style="color:green;margin-bottom:8px">{{ session('success') }}</div>
	@endif

	<!-- Mostrar info de paginación: cuántos por página y total -->
	<div style="display:flex;justify-content:flex-end;gap:12px;margin-bottom:8px;color:#6b7280;font-size:13px">
		<div>Mostrando {{ $providers->perPage() }} por página</div>
		<div>Total: {{ $providers->total() }}</div>
	</div>

	<table style="width:100%;border-collapse:collapse;text-align:center">
		<thead>
			<tr style="text-align:center;border-bottom:1px solid #e6edf3">
				<!-- ID column removed -->
				<th style="padding:8px;text-align:center">Nombre</th>
				<th style="padding:8px;text-align:center">Email</th>
				<th style="padding:8px;text-align:center">Teléfono</th>
				<th style="padding:8px;width:220px;text-align:center">Acciones</th>
			</tr>
		</thead>
		<tbody>
		@forelse($providers as $p)
			<tr style="border-bottom:1px solid #f1f5f9">
				<!-- ID cell removed -->
				<td style="padding:8px;vertical-align:middle;text-align:center">{{ $p->name }}</td>
				<td style="padding:8px;vertical-align:middle;text-align:center">{{ $p->email }}</td>
				<td style="padding:8px;vertical-align:middle;text-align:center">{{ $p->phone }}</td>
				<td style="padding:8px;vertical-align:middle;text-align:center">
					<button type="button"
						class="btn-view"
						data-id="{{ $p->id }}"
						data-name="{{ $p->name }}"
						data-email="{{ $p->email }}"
						data-phone="{{ $p->phone }}"
						data-created="{{ optional($p->created_at)->format('Y-m-d H:i') ?? '' }}"
						{{-- servicios inline (JSON) para mostrar sin hacer fetch si están disponibles --}}
						data-services='@json($p->services->map(function($s){ return ["name"=>$s->name, "description"=>$s->description ?? null, "price" => $s->price ?? null]; }))'
						aria-label="Ver {{ $p->name }}"
						style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:8px;background:#2563eb;color:#fff;border:0;cursor:pointer;font-weight:600;margin-right:8px">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" role="img" aria-hidden="true" style="width:18px;height:18px">
							<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"/>
							<circle cx="12" cy="12" r="3"/>
						</svg>
					</button>

					<!-- editar: ahora abre modal con formulario más moderno -->
					<button type="button"
						class="btn-edit"
						data-action="{{ route('providers.update', $p) }}"
						data-id="{{ $p->id }}"
						data-name="{{ $p->name }}"
						data-email="{{ $p->email }}"
						data-phone="{{ $p->phone }}"
						aria-label="Editar {{ $p->name }}"
						style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:8px;background:#f59e0b;color:#fff;border:0;cursor:pointer;font-weight:600;margin-right:8px">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" role="img" aria-hidden="true" style="width:16px;height:16px">
							<path d="M16.5 3.5l4 4L7 21l-4 1 1-4L16.5 3.5z"/>
						</svg>
					</button>

					<!-- eliminar: ahora boton que abre modal -->
					<button type="button" class="btn-delete" data-action="{{ route('providers.destroy',$p) }}" data-name="{{ $p->name }}" aria-label="Eliminar {{ $p->name }}" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:8px;background:#ef4444;color:#fff;border:0;cursor:pointer;font-weight:600">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" role="img" aria-hidden="true" style="width:16px;height:16px">
							<polyline points="3 6 5 6 21 6"/>
							<path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
							<path d="M10 11v6"/>
							<path d="M14 11v6"/>
						</svg>
					</button>
				</td>
			</tr>
		@empty
			<tr>
				<td colspan="4" style="padding:12px;text-align:center;color:var(--muted)">No hay proveedores.</td>
			</tr>
		@endforelse
		</tbody>
	</table>

	<div style="margin-top:12px;display:flex;justify-content:center;">
		@php
			$p = $providers;
			$current = $p->currentPage();
			$last = $p->lastPage();
			// range to show around current page
			$start = max(1, $current - 2);
			$end = min($last, $current + 2);
		@endphp

		@if($last > 1)
			<nav aria-label="Paginación de proveedores" style="display:flex;align-items:center;gap:10px;font-size:13px;color:#374151">
				<div style="color:#6b7280">Mostrando {{ $p->perPage() }} por página · Total: {{ $p->total() }}</div>

				<ul style="list-style:none;display:flex;gap:6px;margin:0;padding:0;align-items:center">
					{{-- Prev --}}
					<li>
						@if($p->onFirstPage())
							<span aria-hidden="true" style="display:inline-flex;align-items:center;gap:6px;padding:8px 10px;border-radius:8px;background:#f3f4f6;color:#9ca3af">Prev</span>
						@else
							<a href="{{ $p->previousPageUrl() }}" rel="prev" style="display:inline-flex;align-items:center;gap:6px;padding:8px 10px;border-radius:8px;background:#fff;border:1px solid #e6edf3;color:#374151;text-decoration:none">Prev</a>
						@endif
					</li>

					{{-- first page --}}
					<li>
						@if($current == 1)
							<span aria-current="page" style="padding:8px 10px;border-radius:8px;background:#2563eb;color:#fff;font-weight:600">1</span>
						@else
							<a href="{{ $p->url(1) }}" style="padding:8px 10px;border-radius:8px;background:#fff;border:1px solid #e6edf3;color:#374151;text-decoration:none">1</a>
						@endif
					</li>

					{{-- left ellipsis --}}
					@if($start > 2)
						<li><span style="padding:8px 6px;color:#9ca3af">…</span></li>
					@endif

					{{-- middle pages --}}
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

					{{-- right ellipsis --}}
					@if($end < $last - 1)
						<li><span style="padding:8px 6px;color:#9ca3af">…</span></li>
					@endif

					{{-- last page --}}
					@if($last > 1)
						<li>
							@if($current == $last)
								<span aria-current="page" style="padding:8px 10px;border-radius:8px;background:#2563eb;color:#fff;font-weight:600">{{ $last }}</span>
							@else
								<a href="{{ $p->url($last) }}" style="padding:8px 10px;border-radius:8px;background:#fff;border:1px solid #e6edf3;color:#374151;text-decoration:none">{{ $last }}</a>
							@endif
						</li>
					@endif

					{{-- Next --}}
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

	<div id="deleteModal" style="display:none;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;background:rgba(0,0,0,0.4);">
		<div role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle" style="background:#fff;padding:18px;border-radius:10px;max-width:420px;width:90%;box-shadow:0 6px 18px rgba(0,0,0,0.12)">
			<h3 id="deleteModalTitle" style="margin:0 0 8px 0;font-size:18px">Confirmar eliminación</h3>
			<p id="deleteModalText" style="margin:0;color:#374151">¿Eliminar este proveedor?</p>

			<div style="display:flex;justify-content:flex-end;gap:8px;margin-top:16px">
				<button id="cancelDelete" type="button" style="padding:8px 12px;border-radius:8px;background:#e5e7eb;border:0;cursor:pointer">Cancelar</button>
				<button id="confirmDelete" type="button" style="padding:8px 12px;border-radius:8px;background:#ef4444;color:#fff;border:0;cursor:pointer">Eliminar</button>
			</div>
		</div>
	</div>

	<!-- EDITAR: modal con formulario moderno -->
	@include('providers.edit')

	@include('providers.create')

	@include('providers.view')

	<a id="services"></a>
	<p style="margin:8px 0 12px 0;color:#374151;font-size:14px;">
		Nota: Para ver los servicios que tiene un proveedor, consultelos en el apartado 'Ver'.
	</p>

	<!-- formulario oculto único para envío DELETE -->
	<form id="deleteForm" method="POST" style="display:none;">
		@csrf
		@method('DELETE')
	</form>

	<script>
		// Manejo de modales: creación, edición, eliminación y ver
		(function(){
			const baseProvidersUrl = "{{ url('providers') }}";
			const modal = document.getElementById('deleteModal');
			const modalText = document.getElementById('deleteModalText');
			const cancelBtn = document.getElementById('cancelDelete');
			const confirmBtn = document.getElementById('confirmDelete');
			const deleteForm = document.getElementById('deleteForm');

			let currentAction = null;

			// Confirmación eliminación (delegación segura)
			if (modal && modalText) {
				// attach handlers to current .btn-delete elements (if any)
				document.querySelectorAll('.btn-delete').forEach(btn => {
					btn.addEventListener('click', function(){
						currentAction = this.getAttribute('data-action');
						const name = this.getAttribute('data-name') || 'este proveedor';
						modalText.textContent = "¿Eliminar proveedor \"" + name + "\"? Esta acción no se puede deshacer.";
						modal.style.display = 'flex';
					});
				});

				if (cancelBtn) cancelBtn.addEventListener('click', function(){
					modal.style.display = 'none';
					currentAction = null;
				});

				if (confirmBtn) confirmBtn.addEventListener('click', function(){
					if (!currentAction) return;
					if (deleteForm) {
						deleteForm.setAttribute('action', currentAction);
						deleteForm.submit();
					} else {
						// Fallback: do a POST fetch (no JS libs assumed)
						fetch(currentAction, {
							method: 'POST',
							headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '' , 'X-Requested-With': 'XMLHttpRequest' },
							body: new URLSearchParams({ '_method': 'DELETE' })
						}).then(()=> window.location.reload()).catch(()=> window.location.href = currentAction);
					}
				});

				modal.addEventListener('click', function(e){
					if (e.target === modal) {
						modal.style.display = 'none';
						currentAction = null;
					}
				});
			}

			// VIEW modal: obtener elementos de forma segura y sólo operar si existen
			const viewModal = document.getElementById('viewModal');
			const closeView = document.getElementById('closeView');
			const closeViewFooter = document.getElementById('closeViewFooter');

			if (viewModal) {
				document.querySelectorAll('.btn-view').forEach(btn => {
					btn.addEventListener('click', function(){
						// campos dentro del view modal (si existen)
						const nameEl = document.getElementById('view_name');
						const emailEl = document.getElementById('view_email');
						const phoneEl = document.getElementById('view_phone');
						const createdEl = document.getElementById('view_created');

						if (nameEl) nameEl.textContent = this.getAttribute('data-name') || '--';
						if (emailEl) emailEl.textContent = this.getAttribute('data-email') || '--';
						if (phoneEl) phoneEl.textContent = this.getAttribute('data-phone') || '--';
						if (createdEl) createdEl.textContent = this.getAttribute('data-created') || '--';

						viewModal.style.display = 'flex';
					});
				});

				function closeViewModal(){
					viewModal.style.display = 'none';
				}
				if (closeView) closeView.addEventListener('click', closeViewModal);
				if (closeViewFooter) closeViewFooter.addEventListener('click', closeViewModal);
				viewModal.addEventListener('click', function(e){ if (e.target === viewModal) closeViewModal(); });
			}

			// Si la creación falló en el servidor, abrir modal de crear (solo si existen)
			try {
				if (@json($errors->any() && old('_method') !== 'PUT')) {
					setTimeout(function(){
						const createForm = document.getElementById('createForm');
						const createModal = document.getElementById('createModal');
						const formAction = '{{ old("_action") ?: "" }}';
						if (createForm && formAction) createForm.setAttribute('action', formAction);
						if (createModal) {
							createModal.style.display = 'flex';
							try { createModal.querySelector('input[name="name"]').focus(); } catch(e){}
						}
					}, 60);
				}
			} catch(e){
				// defensivo: si falla la comprobación de errores, no bloquear el resto
				console.warn('error comprobando apertura de modal crear', e);
			}

			// Buscador local (degradado) — se ejecuta sólo si los elementos existen
			(function(){
				const searchInput = document.getElementById('searchInput');
				const tableBody = document.querySelector('table tbody');

				if (!searchInput || !tableBody) return;

				function debounce(fn, wait=180){
					let t;
					return (...args) => { clearTimeout(t); t = setTimeout(()=> fn.apply(this, args), wait); };
				}

				function filterRows(query){
					const q = (query || '').trim().toLowerCase();
					const rows = tableBody.querySelectorAll('tr');
					let visibleCount = 0;

					rows.forEach(row => {
						const cols = row.querySelectorAll('td');
						if (!cols.length) return;

						const rowText = Array.from(cols).map(td => td.textContent.trim().toLowerCase()).join(' ');
						const match = q === '' || rowText.indexOf(q) !== -1;

						row.style.display = match ? '' : 'none';
						if (match) visibleCount++;
					});
				}

				const debouncedFilter = debounce(function(){
					filterRows(searchInput.value);
				}, 180);

				searchInput.addEventListener('keydown', function(e){
					if (e.key === 'Enter') e.preventDefault();
				});

				searchInput.addEventListener('input', debouncedFilter);

				if (searchInput.value) filterRows(searchInput.value);
			})();

		})(); 
	</script>
</div>
@endsection
