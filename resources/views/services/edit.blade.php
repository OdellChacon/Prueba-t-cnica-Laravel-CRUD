<!-- Partial: edit modal + JS para servicios (reutilizable, no extiende layout, no usa $service) -->
<div id="editServiceModal" style="display:none;position:fixed;inset:0;z-index:10000;align-items:center;justify-content:center;background:rgba(0,0,0,0.45);">
	<div role="dialog" aria-modal="true" aria-labelledby="editServiceTitle"
	     style="background:#fff;padding:18px;border-radius:10px;max-width:520px;width:94%;box-shadow:0 8px 30px rgba(2,6,23,0.2);max-height:calc(100vh - 80px);overflow:auto;box-sizing:border-box;">
		<h3 id="editServiceTitle" style="margin:0 0 12px 0;font-size:18px">Editar servicio</h3>

		<form id="editServiceForm" method="POST" novalidate style="display:flex;flex-direction:column;gap:10px">
			@csrf
			@method('PUT')

			<input type="hidden" name="id" value="{{ old('id','') }}">
			<input type="hidden" name="_action" value="{{ old('_action','') }}">

			<label style="display:block;font-size:13px;color:#374151;">
				Nombre
				<input name="name" id="edit_service_name" type="text" required style="margin-top:6px;width:100%;padding:10px;border-radius:8px;border:1px solid #e5e7eb;font-size:14px;box-sizing:border-box;display:block;" />
				<div id="edit_service_name_error" style="color:#dc2626;font-size:13px;margin-top:6px;display:none"></div>
			</label>

			<label style="display:block;font-size:13px;color:#374151;">
				Duración (min)
				<input name="duration_minutes" id="edit_service_duration" type="number" min="1" required style="margin-top:6px;width:100%;padding:10px;border-radius:8px;border:1px solid #e5e7eb;font-size:14px;box-sizing:border-box;display:block;" />
				<div id="edit_service_duration_error" style="color:#dc2626;font-size:13px;margin-top:6px;display:none"></div>
			</label>

			<label style="display:block;font-size:13px;color:#374151;">
				Precio
				<input name="price" id="edit_service_price" type="number" step="0.01" min="0" required style="margin-top:6px;width:100%;padding:10px;border-radius:8px;border:1px solid #e5e7eb;font-size:14px;box-sizing:border-box;display:block;" />
				<div id="edit_service_price_error" style="color:#dc2626;font-size:13px;margin-top:6px;display:none"></div>
			</label>

			<div style="display:flex;justify-content:flex-end;gap:8px;margin-top:6px">
				<button type="button" id="cancelEditService" style="padding:8px 12px;border-radius:8px;background:#f3f4f6;border:0;cursor:pointer">Cancelar</button>
				<button type="submit" id="saveEditService" style="padding:8px 12px;border-radius:8px;background:#059669;color:#fff;border:0;cursor:pointer">Guardar</button>
			</div>
		</form>
	</div>
</div>

<script>
	(function(){
		const modal = document.getElementById('editServiceModal');
		const form = document.getElementById('editServiceForm');
		const btnCancel = document.getElementById('cancelEditService');

		function clearErrors(){
			['edit_service_name_error','edit_service_duration_error','edit_service_price_error'].forEach(id=>{
				const el = document.getElementById(id);
				if(el){ el.textContent=''; el.style.display='none'; }
			});
		}

		function validate(){
			clearErrors();
			const name = (document.getElementById('edit_service_name')?.value || '').trim();
			const duration = (document.getElementById('edit_service_duration')?.value || '').trim();
			const price = (document.getElementById('edit_service_price')?.value || '').trim();

			let firstInvalid = null;

			if (!name) { const el=document.getElementById('edit_service_name_error'); if(el){ el.textContent='El nombre es obligatorio.'; el.style.display='block'; } firstInvalid = firstInvalid || document.getElementById('edit_service_name'); }
			if (!duration || isNaN(duration) || parseInt(duration,10) < 1) { const el=document.getElementById('edit_service_duration_error'); if(el){ el.textContent='Duración inválida.'; el.style.display='block'; } firstInvalid = firstInvalid || document.getElementById('edit_service_duration'); }
			if (price === '' || isNaN(price) || parseFloat(price) < 0) { const el=document.getElementById('edit_service_price_error'); if(el){ el.textContent='Precio inválido.'; el.style.display='block'; } firstInvalid = firstInvalid || document.getElementById('edit_service_price'); }

			return firstInvalid;
		}

		// Delegación: abrir modal cuando se hace click en cualquier .btn-edit (dinámico)
		document.addEventListener('click', function(e){
			const btn = e.target.closest && e.target.closest('.btn-edit');
			if (!btn) return;
			if (!modal || !form) return;
			e.preventDefault();

			const action = btn.getAttribute('data-action') || '';
			const id = btn.getAttribute('data-id') || '';
			const name = btn.getAttribute('data-name') || '';
			const duration = btn.getAttribute('data-duration') || '';
			const price = btn.getAttribute('data-price') || '';

			if (action) {
				try { form.setAttribute('action', action); } catch(e){}
				const actionInput = form.querySelector('input[name="_action"]');
				if (actionInput) actionInput.value = action;
			}
			const idInput = form.querySelector('input[name="id"]');
			if (idInput) idInput.value = id;

			const nameInput = form.querySelector('input[name="name"]') || document.getElementById('edit_service_name');
			const durationInput = document.getElementById('edit_service_duration');
			const priceInput = document.getElementById('edit_service_price');

			if (nameInput) nameInput.value = name;
			if (durationInput) durationInput.value = duration;
			if (priceInput) priceInput.value = price;

			clearErrors();
			modal.style.display = 'flex';
			setTimeout(()=> { try { (form.querySelector('input[name="name"]')||{}).focus(); } catch(e){} }, 40);
		});

		if (btnCancel) btnCancel.addEventListener('click', function(){ if (modal) modal.style.display = 'none'; });

		if (modal) modal.addEventListener('click', function(e){ if (e.target === modal) modal.style.display = 'none'; });

		// Envío por AJAX: evita redirecciones del servidor y muestra errores dentro del modal
		if (form) {
			form.addEventListener('submit', async function(e){
				// validación cliente primero
				const firstInvalid = validate();
				if (firstInvalid) {
					e.preventDefault();
					firstInvalid.focus();
					return;
				}
				e.preventDefault();

				// obtener acción (ruta) del form
				const action = form.getAttribute('action') || form.querySelector('input[name="_action"]')?.value || '';
				if (!action) {
					alert('No está definida la ruta para actualizar el servicio.');
					return;
				}

				// construir payload (incluye _method=PUT y _token gracias a FormData)
				const formData = new FormData(form);
				// Asegurar que _method=PUT esté presente
				if (!formData.has('_method')) formData.append('_method', 'PUT');

				// CSRF token desde meta o desde formulario
				const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || formData.get('_token') || '';

				try {
					const res = await fetch(action, {
						method: 'POST',
						headers: {
							'X-Requested-With': 'XMLHttpRequest',
							'X-CSRF-TOKEN': token,
							'Accept': 'application/json'
						},
						body: new URLSearchParams([...formData.entries()])
					});

					if (res.status === 422) {
						// Validación: mostrar errores dentro del modal
						const json = await res.json().catch(()=>null);
						const errors = json?.errors || {};
						// limpiar y luego mostrar
						clearErrors();
						Object.keys(errors).forEach(field => {
							const errMsg = Array.isArray(errors[field]) ? errors[field][0] : (errors[field] || '');
							const el = document.getElementById('edit_service_' + field + '_error') || document.getElementById('edit_service_' + field + '_error');
							// mapeo de nombres comunes
							let targetId = null;
							if (field === 'name') targetId = 'edit_service_name_error';
							if (field === 'duration_minutes') targetId = 'edit_service_duration_error';
							if (field === 'price') targetId = 'edit_service_price_error';
							const targetEl = targetId ? document.getElementById(targetId) : el;
							if (targetEl) { targetEl.textContent = errMsg; targetEl.style.display = 'block'; }
						});
						// focus en primer error visible
						const firstErr = document.querySelector('[id$="_error"]:not([style*="display: none"])');
						if (firstErr) {
							const inputId = firstErr.id.replace('_error','');
							const inputEl = document.getElementById(inputId);
							if (inputEl) inputEl.focus();
						}
						return;
					}

					if (res.ok) {
						// éxito: cerrar modal y recargar lista para reflejar cambios
						if (modal) modal.style.display = 'none';
						// Opcional: actualizar fila en DOM en lugar de recargar; por simplicidad recargamos
						window.location.reload();
						return;
					}

					// otro código de error: intentar obtener texto y notificar
					const txt = await res.text().catch(()=>null);
					console.error('Error actualizando servicio:', res.status, txt);
					alert('Error al guardar. Revise la consola para más detalles.');
				} catch (err) {
					console.error('Error de red al guardar servicio:', err);
					alert('Error de red al guardar. Intente de nuevo.');
				}
			});
		}

		// Si la validación del servidor falló al hacer PUT, reabrir modal editar y poblar campos
		@if($errors->any() && old('_method') === 'PUT')
			setTimeout(function(){
				const oldAction = '{{ old("_action") ?: "" }}';
				const oldId = '{{ old("id","") }}';
				if (oldAction) try{ form.setAttribute('action', oldAction); } catch(e){}
				if (!oldAction && oldId) {
					try { form.setAttribute('action', '{{ url("services") }}/' + oldId); } catch(e){}
				}
				try {
					const nameInput = document.getElementById('edit_service_name');
					const durationInput = document.getElementById('edit_service_duration');
					const priceInput = document.getElementById('edit_service_price');
					if (nameInput) nameInput.value = '{{ addslashes(old("name","")) }}';
					if (durationInput) durationInput.value = '{{ addslashes(old("duration_minutes","")) }}';
					if (priceInput) priceInput.value = '{{ addslashes(old("price","")) }}';
					const idInput = form.querySelector('input[name="id"]');
					if (idInput) idInput.value = oldId;
				} catch(e){}
				// mostrar mensajes de error del servidor si los hay
				(function(){
					try {
						const nameErr = {!! json_encode($errors->first('name')) !!};
						const durationErr = {!! json_encode($errors->first('duration_minutes')) !!};
						const priceErr = {!! json_encode($errors->first('price')) !!};
						if (nameErr) { const el=document.getElementById('edit_service_name_error'); if(el){ el.textContent = nameErr; el.style.display='block'; } }
						if (durationErr) { const el=document.getElementById('edit_service_duration_error'); if(el){ el.textContent = durationErr; el.style.display='block'; } }
						if (priceErr) { const el=document.getElementById('edit_service_price_error'); if(el){ el.textContent = priceErr; el.style.display='block'; } }
					} catch(e){}
				})();
				if (modal) modal.style.display = 'flex';
				try{ document.getElementById('edit_service_name')?.focus(); } catch(e){}
			}, 60);
		@endif
	})();
</script>
