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

<!-- formulario oculto único para envío DELETE -->
<form id="deleteForm" method="POST" style="display:none;">
	@csrf
	@method('DELETE')
</form>

<script>
	// JS autocontenido para el modal "Eliminar" (delegación)
	(function(){
		const modal = document.getElementById('deleteModal');
		const modalText = document.getElementById('deleteModalText');
		const cancelBtn = document.getElementById('cancelDelete');
		const confirmBtn = document.getElementById('confirmDelete');
		const deleteForm = document.getElementById('deleteForm');

		let currentAction = null;

		// Delegación global: detecta clicks en botones .btn-delete incluso si se añaden dinámicamente
		document.addEventListener('click', function(e){
			const btn = e.target.closest && e.target.closest('.btn-delete');
			if (!btn) return;
			e.preventDefault();
			currentAction = btn.getAttribute('data-action');
			const name = btn.getAttribute('data-name') || 'este proveedor';
			if (modalText) modalText.textContent = "¿Eliminar proveedor \"" + name + "\"? Esta acción no se puede deshacer.";
			if (modal) modal.style.display = 'flex';
		});

		if (cancelBtn) cancelBtn.addEventListener('click', function(){ if (modal) modal.style.display = 'none'; currentAction = null; });

		if (confirmBtn) confirmBtn.addEventListener('click', function(){
			if (!currentAction) return;
			if (deleteForm) {
				deleteForm.setAttribute('action', currentAction);
				deleteForm.submit();
			} else {
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
