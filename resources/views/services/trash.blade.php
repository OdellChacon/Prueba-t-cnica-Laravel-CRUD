@extends('layouts.app')
@section('title','Servicios eliminados')
@section('subtitle','Trash')
@section('content')
<div class="card">
	<!-- Volver -->
	<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
		<a href="{{ route('services.index') }}" class="btn-back" aria-label="Volver a servicios" style="display:inline-flex;align-items:center;gap:8px;padding:8px 12px;border-radius:8px;background:#f3f4f6;color:#111827;text-decoration:none;border:1px solid #e5e7eb">
			<svg width="16" height="16" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M9 15l-5-5 5-5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M15 10H4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
			Volver
		</a>
		<div style="color:#6b7280;font-size:13px">Mostrando {{ $services->count() }} de {{ $services->total() }}</div>
	</div>

	@if(session('success'))<div style="color:green;margin-bottom:8px">{{ session('success') }}</div>@endif

	<style>
		.services-grid { display:grid; grid-template-columns: repeat(auto-fit,minmax(240px,1fr)); gap:12px; }
		@media (min-width: 900px) { .services-grid { grid-template-columns: repeat(3, 1fr); } }
		.service-card { background:#fff;border:1px solid #e6edf3;padding:12px;border-radius:10px;box-shadow:0 1px 2px rgba(15,23,42,0.03); display:flex;flex-direction:column;justify-content:space-between; }
		.service-title { font-weight:600;color:#0f172a; margin-bottom:6px; }
		.service-meta { color:#6b7280;font-size:13px; }
		.actions { display:flex;gap:8px;justify-content:flex-end;margin-top:10px; }
		.btn-restore-ui {
			display:inline-flex;align-items:center;gap:8px;padding:10px 12px;border-radius:10px;background:#10b981;color:#fff;border:0;cursor:pointer;font-weight:700;font-size:14px;box-shadow: 0 6px 18px rgba(16,185,129,0.08);transition: transform .06s, box-shadow .12s;
		}
		.btn-restore-ui:hover { background:#0ea76f; transform: translateY(-1px); box-shadow: 0 10px 30px rgba(16,185,129,0.12); }
	</style>

	<div class="services-grid" role="list">
	@foreach($services as $s)
		<article class="service-card" role="listitem" aria-label="Servicio {{ $s->name }}">
			<div>
				<div style="display:flex;justify-content:space-between;align-items:start;gap:8px;">
					<div style="flex:1">
						<div class="service-title">{{ $s->name }}</div>
						<div class="service-meta">Proveedor: {{ optional($s->provider)->name ?? '—' }}</div>
					</div>
					<div style="text-align:right;color:#6b7280;font-size:13px;">
						<div>Eliminado: {{ $s->deleted_at }}</div>
					</div>
				</div>

				<div style="margin-top:8px;color:#6b7280;font-size:13px;display:flex;gap:8px;flex-wrap:wrap;">
					<div>Duración: <strong style="color:#111827">{{ $s->duration_minutes }} min</strong></div>
					<div>Precio: <strong style="color:#111827">${{ number_format($s->price,2) }}</strong></div>
				</div>
			</div>

			<div class="actions">
				<form method="POST" action="{{ route('services.restore', $s->id) }}" style="display:none;">
					@csrf @method('PUT')
				</form>

				<button type="button" class="btn-restore-ui" data-action="{{ route('services.restore', $s->id) }}" data-name="{{ $s->name }}" aria-label="Restaurar {{ $s->name }}">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M21 12a9 9 0 1 0-3.36 6.36" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 3v6h-6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
					Restaurar
				</button>
			</div>
		</article>
	@endforeach
	</div>

	@php
		$p = $services;
		$current = $p->currentPage();
		$last = $p->lastPage();
		$start = max(1, $current - 2);
		$end = min($last, $current + 2);
	@endphp

	@if($last > 1)
		<nav aria-label="Paginación de servicios eliminados" style="margin-top:12px;display:flex;justify-content:center;align-items:center;gap:12px">
			<div style="color:#6b7280;font-size:13px">Mostrando {{ $p->perPage() }} por página · Total: {{ $p->total() }}</div>
			<ul style="list-style:none;display:flex;gap:6px;margin:0;padding:0;align-items:center">
				<li>@if($p->onFirstPage())<span style="padding:8px 10px;border-radius:8px;background:#f3f4f6;color:#9ca3af">Prev</span>@else<a href="{{ $p->previousPageUrl() }}" rel="prev" style="padding:8px 10px;border-radius:8px;background:#fff;border:1px solid #e6edf3;color:#374151;text-decoration:none">Prev</a>@endif</li>
				<li>@if($current == 1)<span aria-current="page" style="padding:8px 10px;border-radius:8px;background:#10b981;color:#fff;font-weight:600">1</span>@else<a href="{{ $p->url(1) }}" style="padding:8px 10px;border-radius:8px;background:#fff;border:1px solid #e6edf3;color:#374151;text-decoration:none">1</a>@endif</li>
				@if($start > 2)<li><span style="padding:8px 6px;color:#9ca3af">…</span></li>@endif
				@for($i = $start; $i <= $end; $i++)
					@if($i == 1 || $i == $last) @continue @endif
					<li>@if($i == $current)<span style="padding:8px 10px;border-radius:8px;background:#10b981;color:#fff;font-weight:600">{{ $i }}</span>@else<a href="{{ $p->url($i) }}" style="padding:8px 10px;border-radius:8px;background:#fff;border:1px solid #e6edf3;color:#374151;text-decoration:none">{{ $i }}</a>@endif</li>
				@endfor
				@if($end < $last - 1)<li><span style="padding:8px 6px;color:#9ca3af">…</span></li>@endif
				<li>@if($current == $last)<span style="padding:8px 10px;border-radius:8px;background:#10b981;color:#fff;font-weight:600">{{ $last }}</span>@else<a href="{{ $p->url($last) }}" style="padding:8px 10px;border-radius:8px;background:#fff;border:1px solid #e6edf3;color:#374151;text-decoration:none">{{ $last }}</a>@endif</li>
				<li>@if($p->hasMorePages())<a href="{{ $p->nextPageUrl() }}" rel="next" style="padding:8px 10px;border-radius:8px;background:#10b981;color:#fff;text-decoration:none;font-weight:600">Siguiente</a>@else<span style="padding:8px 10px;border-radius:8px;background:#f3f4f6;color:#9ca3af">Siguiente</span>@endif</li>
			</ul>
		</nav>
	@endif
</div>

<!-- formulario oculto único para envío RESTORE -->
<form id="restoreForm" method="POST" style="display:none;">
	@csrf
	@method('PUT')
</form>

<!-- modal de confirmación para restaurar -->
<dialog id="confirmRestoreDialog" aria-labelledby="confirmRestoreTitle" style="padding:0;border-radius:10px;border:0;max-width:420px;width:92%;">
	<form method="dialog" style="display:block;">
		<header style="padding:16px;border-bottom:1px solid #eef2f7;display:flex;justify-content:space-between;align-items:center">
			<h3 id="confirmRestoreTitle" style="margin:0;font-size:16px">Confirmar restauración</h3>
			<button id="confirmClose" type="button" aria-label="Cerrar" style="background:transparent;border:0;font-size:18px;cursor:pointer;color:#6b7280">&times;</button>
		</header>
		<main style="padding:14px;color:#111827;font-size:14px">
			<p style="margin:0 0 8px">¿Deseas restaurar este servicio?</p>
			<p id="confirmServiceName" style="margin:0;color:#374151;font-weight:600">--</p>
		</main>
		<footer style="display:flex;gap:8px;justify-content:flex-end;padding:12px;border-top:1px solid #eef2f7">
			<button id="cancelRestoreBtn" type="button" style="padding:8px 12px;border-radius:8px;background:#e5e7eb;border:0;cursor:pointer">Cancelar</button>
			<button id="confirmRestoreBtn" type="button" style="padding:8px 12px;border-radius:8px;background:#10b981;color:#fff;border:0;cursor:pointer;font-weight:700">Confirmar</button>
		</footer>
	</form>
</dialog>

<script>
	(function(){
		const restoreForm = document.getElementById('restoreForm');
		const confirmDialog = document.getElementById('confirmRestoreDialog');
		const confirmServiceName = document.getElementById('confirmServiceName');
		const confirmBtn = document.getElementById('confirmRestoreBtn');
		const cancelBtn = document.getElementById('cancelRestoreBtn');
		const closeX = document.getElementById('confirmClose');
		let pendingAction = null;
		let lastFocused = null;

		function openConfirm(name, action) {
			pendingAction = action;
			confirmServiceName.textContent = name || 'Servicio';
			lastFocused = document.activeElement;
			if (typeof confirmDialog.showModal === 'function') confirmDialog.showModal();
			else confirmDialog.setAttribute('open','');
			confirmBtn.focus();
		}

		function closeConfirm() {
			try { if (typeof confirmDialog.close === 'function' && confirmDialog.open) confirmDialog.close(); else confirmDialog.removeAttribute('open'); } catch(e){}
			pendingAction = null;
			if (lastFocused) lastFocused.focus();
		}

		function doRestore(action) {
			if (!action) return alert('No se pudo determinar la acción de restauración.');
			restoreForm.setAttribute('action', action);
			restoreForm.submit();
		}

		document.addEventListener('click', function(e){
			const btn = e.target.closest('.btn-restore-ui, .btn-restore');
			if (!btn) return;
			e.preventDefault();
			if (btn.getAttribute('aria-disabled') === 'true') return;
			const action = btn.getAttribute('data-action') || '';
			const name = btn.getAttribute('data-name') || btn.closest('.service-card')?.querySelector('.service-title')?.textContent || '';
			let resolvedAction = action;
			if (!resolvedAction) {
				const id = btn.getAttribute('data-id');
				if (id) resolvedAction = '/services/' + encodeURIComponent(id) + '/restore';
			}
			openConfirm(name, resolvedAction);
		});

		confirmBtn.addEventListener('click', function(){ if (pendingAction) doRestore(pendingAction); });
		cancelBtn.addEventListener('click', closeConfirm);
		closeX.addEventListener('click', closeConfirm);
		confirmDialog.addEventListener('click', function(e){ if (e.target === confirmDialog) closeConfirm(); });
		confirmDialog.addEventListener('cancel', function(e){ e.preventDefault(); closeConfirm(); });
		document.addEventListener('keydown', function(e){ if (e.key === 'Escape' && (confirmDialog.hasAttribute('open') || confirmDialog.open)) closeConfirm(); });
	})();
</script>
@endsection
