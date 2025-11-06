@extends('layouts.app')
@section('title','Servicios')
@section('subtitle',$provider->name ?? 'Servicios')
@section('content')

<div class="card" style="background:#fff;">
	<!-- ...existing header/buttons ... -->
	<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
		<div style="display:flex;align-items:center;gap:10px;">
			<a href="{{ route('providers.services.create', $provider) }}" style="display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border-radius:10px;background:linear-gradient(90deg,#10b981,#059669);color:#fff;font-weight:700;text-decoration:none;box-shadow:0 8px 20px rgba(5,150,105,0.12);border:0">
				<!-- plus icon -->
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
				<span>Nuevo servicio</span>
			</a>

			<a href="{{ route('services.trash') }}" style="display:inline-flex;align-items:center;gap:8px;padding:8px 10px;border-radius:10px;background:transparent;color:#ef4444;text-decoration:none;font-weight:600;border:1px solid rgba(239,68,68,0.08)">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
				<span>Ver eliminados</span>
			</a>
		</div>

		<div style="display:flex;align-items:center;gap:8px;">
			<form method="GET" action="{{ route('providers.services.index', $provider) }}" style="display:flex;align-items:center;gap:8px;">
				<div style="display:flex;align-items:center;background:#fff;padding:6px 8px;border-radius:10px;border:1px solid #e6edf3;box-shadow:0 6px 18px rgba(2,6,23,0.04);">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;margin-right:6px;flex-shrink:0"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
					<input id="searchInput" type="search" name="q" value="{{ request('q') }}" placeholder="Buscar servicios..." title="Buscar servicios" aria-label="Buscar servicios" autocomplete="off" style="border:0;outline:none;padding:8px 6px;font-size:14px;min-width:220px;background:transparent;box-sizing:border-box">
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

	<!-- GRID de servicios (cards) -->
	<div id="servicesGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:12px;">
		@forelse($services as $s)
			<article class="service-card" data-name="{{ $s->name }}" data-duration="{{ $s->duration_minutes }}" data-price="{{ $s->price }}" style="background:#fff;border-radius:10px;padding:12px;border:1px solid #e6edf3;box-shadow:0 8px 20px rgba(2,6,23,0.04);display:flex;flex-direction:column;justify-content:space-between;min-height:120px">
				<div>
					<div style="display:flex;justify-content:space-between;align-items:start;gap:8px;">
						<h3 style="margin:0;font-size:16px;color:#111827;">{{ $s->name }}</h3>
						<div style="color:#6b7280;font-size:13px;">{{ optional($s->provider)->name ?? '—' }}</div>
					</div>

					<div style="margin-top:8px;color:#6b7280;font-size:13px;display:flex;gap:10px;flex-wrap:wrap;">
						<div>Duración: <strong style="color:#111827">{{ $s->duration_minutes }} min</strong></div>
						<div>Precio: <strong style="color:#111827">${{ number_format($s->price,2) }}</strong></div>
					</div>
				</div>

				<div style="display:flex;justify-content:flex-end;gap:8px;margin-top:12px">
					<button type="button"
						class="btn-edit"
						data-action="{{ route('services.update', $s) }}"
						data-id="{{ $s->id }}"
						data-name="{{ $s->name }}"
						data-duration="{{ $s->duration_minutes }}"
						data-price="{{ $s->price }}"
						aria-label="Editar {{ $s->name }}"
						style="display:inline-flex;align-items:center;justify-content:center;padding:8px 10px;border-radius:8px;background:#f59e0b;color:#fff;border:0;cursor:pointer;font-weight:600">
						Editar
					</button>

					<button type="button"
						class="btn-delete"
						data-action="{{ route('services.destroy', $s) }}"
						data-name="{{ $s->name }}"
						aria-label="Eliminar {{ $s->name }}"
						style="display:inline-flex;align-items:center;justify-content:center;padding:8px 10px;border-radius:8px;background:#ef4444;color:#fff;border:0;cursor:pointer;font-weight:600">
						Eliminar
					</button>
				</div>
			</article>
		@empty
			<div style="grid-column:1/-1;padding:12px;text-align:center;color:var(--muted)">No hay servicios.</div>
		@endforelse
	</div>

	<!-- paginación -->
	<div style="margin-top:12px;display:flex;justify-content:center;">
		{{ $services->links() }}
	</div>

	{{-- include modal edit (partial) --}}
	@include('services.edit')

	{{-- Modal de confirmación para eliminar servicios local a esta vista --}}
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

	<!-- script: búsqueda local (grid) + delegación eliminar -->
	<script>
		(function(){
			// buscador debounce para grid
			const input = document.getElementById('searchInput');
			const grid = document.getElementById('servicesGrid');
			function debounce(fn, ms=250){ let t; return (...a)=>{ clearTimeout(t); t=setTimeout(()=>fn.apply(this,a), ms); }; }

			if (input && grid) {
				const filter = debounce(() => {
					const q = (input.value || '').trim().toLowerCase();
					if (!q) {
						// restore by showing all (don't reload)
						grid.querySelectorAll('.service-card').forEach(c => c.style.display = '');
						return;
					}
					grid.querySelectorAll('.service-card').forEach(card => {
						const name = (card.getAttribute('data-name')||'').toLowerCase();
						const duration = (card.getAttribute('data-duration')||'').toLowerCase();
						const price = (card.getAttribute('data-price')||'').toLowerCase();
						const match = name.indexOf(q) !== -1 || duration.indexOf(q) !== -1 || price.indexOf(q) !== -1;
						card.style.display = match ? '' : 'none';
					});
				}, 250);

				input.addEventListener('input', filter);
				input.addEventListener('keydown', e=>{ if (e.key === 'Enter') e.preventDefault(); });
			}

			// eliminar: delegación y modal
			const modal = document.getElementById('deleteServiceModal');
			const textEl = document.getElementById('deleteServiceText');
			const btnCancel = document.getElementById('cancelDeleteService');
			const btnConfirm = document.getElementById('confirmDeleteService');
			const hiddenForm = document.getElementById('deleteServiceForm');
			let currentAction = null;

			// delegación global para .btn-delete
			document.addEventListener('click', function(e){
				const btn = e.target.closest && e.target.closest('.btn-delete');
				if (!btn) return;
				e.preventDefault();
				currentAction = btn.getAttribute('data-action');
				const name = btn.getAttribute('data-name') || 'este servicio';
				if (textEl) textEl.textContent = '¿Eliminar el servicio "' + name + '"? Esta acción no se puede deshacer.';
				if (modal) modal.style.display = 'flex';
			});

			if (btnCancel) btnCancel.addEventListener('click', ()=>{ if (modal) modal.style.display='none'; currentAction = null; });

			if (btnConfirm) btnConfirm.addEventListener('click', ()=> {
				if (!currentAction) return;
				if (hiddenForm) {
					hiddenForm.setAttribute('action', currentAction);
					hiddenForm.submit();
				} else {
					const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
					fetch(currentAction, {
						method: 'POST',
						headers: { 'X-CSRF-TOKEN': token, 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded' },
						body: new URLSearchParams({ '_method': 'DELETE' })
					}).then(()=> window.location.reload()).catch(()=> window.location.href = currentAction);
				}
			});

			if (modal) modal.addEventListener('click', function(e){ if (e.target === modal) { modal.style.display='none'; currentAction = null; } });
		})();
	</script>

</div>

@endsection
