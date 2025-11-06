{{-- fragmento: modal + formulario de creación (reutilizable) --}}
@php
    // $serviceStoreAllowsPost y $provider pueden ser pasados desde la vista que incluye este fragmento
    $serviceStoreAllowsPost = $serviceStoreAllowsPost ?? false;
@endphp

<div id="createServiceModal" style="display:none;position:fixed;inset:0;z-index:10003;align-items:center;justify-content:center;background:rgba(0,0,0,0.45);">
	<div role="dialog" aria-modal="true" aria-labelledby="createServiceTitle"
	     style="background:#fff;padding:18px;border-radius:10px;max-width:520px;width:94%;box-shadow:0 8px 30px rgba(2,6,23,0.2);max-height:calc(100vh - 80px);overflow:auto;box-sizing:border-box;">
		<h3 id="createServiceTitle" style="margin:0 0 12px 0;font-size:18px">Nuevo servicio</h3>

		<form id="createServiceForm" method="POST" novalidate style="display:flex;flex-direction:column;gap:10px">
			@csrf

			{{-- Si se abrió desde un proveedor concreto, recibimos $provider --}}
			@if(!empty($provider))
				<input type="hidden" name="provider_id" value="{{ $provider->id }}">
			@else
				{{-- lista de proveedores (puede venir vacía). el JS construirá la action con el id seleccionado --}}
				<label style="display:block;font-size:13px;color:#374151;">
					Proveedor
					<select name="provider_id" id="create_service_provider" style="margin-top:6px;width:100%;padding:10px;border-radius:8px;border:1px solid #e5e7eb;font-size:14px;box-sizing:border-box;">
						<option value="">-- Seleccione proveedor --</option>
						@foreach(($providers ?? []) as $prov)
							<option value="{{ $prov->id }}" {{ old('provider_id') == $prov->id ? 'selected' : '' }}>{{ $prov->name }}</option>
						@endforeach
					</select>
					<div id="create_service_provider_error" style="color:#dc2626;font-size:13px;margin-top:6px;display:none"></div>
				</label>
			@endif

			<label style="display:block;font-size:13px;color:#374151;">
				Nombre
				<input name="name" id="create_service_name" type="text" required value="{{ old('name') }}" style="margin-top:6px;width:100%;padding:10px;border-radius:8px;border:1px solid #e5e7eb;font-size:14px;box-sizing:border-box;display:block" />
				<div id="create_service_name_error" style="color:#dc2626;font-size:13px;margin-top:6px;display:none"></div>
			</label>

			<label style="display:block;font-size:13px;color:#374151;">
				Duración (min)
				<input name="duration_minutes" id="create_service_duration" type="number" min="1" required value="{{ old('duration_minutes', 30) }}" style="margin-top:6px;width:100%;padding:10px;border-radius:8px;border:1px solid #e5e7eb;font-size:14px;box-sizing:border-box;display:block" />
				<div id="create_service_duration_error" style="color:#dc2626;font-size:13px;margin-top:6px;display:none"></div>
			</label>

			<label style="display:block;font-size:13px;color:#374151;">
				Precio
				<input name="price" id="create_service_price" type="number" step="0.01" min="0" required value="{{ old('price', '0.00') }}" style="margin-top:6px;width:100%;padding:10px;border-radius:8px;border:1px solid #e5e7eb;font-size:14px;box-sizing:border-box;display:block" />
				<div id="create_service_price_error" style="color:#dc2626;font-size:13px;margin-top:6px;display:none"></div>
			</label>

			<div style="display:flex;justify-content:flex-end;gap:8px;margin-top:6px">
				<button type="button" id="cancelCreateService" style="padding:8px 12px;border-radius:8px;background:#f3f4f6;border:0;cursor:pointer">Cancelar</button>
				<button type="submit" id="saveCreateService" style="padding:8px 12px;border-radius:8px;background:#059669;color:#fff;border:0;cursor:pointer">Crear</button>
			</div>
		</form>
	</div>
</div>

<script>
	(function(){
		const openBtn = document.getElementById('btnOpenCreateService');
		const modal = document.getElementById('createServiceModal');
		const form = document.getElementById('createServiceForm');
		const cancelBtn = document.getElementById('cancelCreateService');

		// helper validations (simple)
		function showError(id, msg){
			const el = document.getElementById(id);
			if (!el) return;
			el.textContent = msg;
			el.style.display = msg ? 'block' : 'none';
		}
		function clearErrors(){
			['create_service_provider_error','create_service_name_error','create_service_duration_error','create_service_price_error'].forEach(id=>{
				const el = document.getElementById(id); if (el){ el.textContent=''; el.style.display='none'; }
			});
		}

		if (openBtn) {
			openBtn.addEventListener('click', function(){
				// si el modal se abrió desde la vista de proveedor, el campo provider_id ya está oculto en el form
				// de lo contrario el usuario debe seleccionar proveedor.
				clearErrors();
				// No fijamos action aquí: lo construiremos en submit basándonos en provider_id seleccionado
				modal.style.display = 'flex';
				try { form.querySelector('input[name="name"]').focus(); } catch(e){}
			});
		}

		if (cancelBtn) cancelBtn.addEventListener('click', ()=> modal.style.display = 'none');
		modal.addEventListener('click', function(e){ if (e.target === modal) modal.style.display = 'none'; });

		if (form) {
			form.addEventListener('submit', function(e){
				clearErrors();

				// si provider_id no está presente (campo oculto), buscar elemento select
				const providerInput = form.querySelector('input[name="provider_id"]') || form.querySelector('select[name="provider_id"]');
				const name = (form.querySelector('input[name="name"]')?.value || '').trim();
				const duration = (form.querySelector('input[name="duration_minutes"]')?.value || '').trim();
				const price = (form.querySelector('input[name="price"]')?.value || '').trim();

				let invalid = false;
				// provider required when not prefilled
				if (providerInput && providerInput.tagName === 'SELECT' && !providerInput.value) {
					showError('create_service_provider_error','Seleccione un proveedor.');
					invalid = true;
				}
				if (!name) { showError('create_service_name_error','El nombre es obligatorio.'); invalid = true; }
				if (!duration || Number(duration) < 1) { showError('create_service_duration_error','Duración válida requerida.'); invalid = true; }
				if (price === '' || Number(price) < 0) { showError('create_service_price_error','Precio válido requerido.'); invalid = true; }

				if (invalid) { e.preventDefault(); return; }

				// Build action dynamically if needed: if the form has an input[name="provider_id"] hidden then no need.
				const hiddenProv = form.querySelector('input[name="provider_id"]');
				if (!hiddenProv) {
					const sel = form.querySelector('select[name="provider_id"]');
					if (sel && sel.value) {
						// construir /providers/{id}/services
						const action = '{{ url('providers') }}/' + encodeURIComponent(sel.value) + '/services';
						form.setAttribute('action', action);
						// allow submit
						return;
					} else {
						// shouldn't happen por validación anterior
						e.preventDefault();
					}
				}
				// si hiddenProv existe, la acción debe setearse por la vista que incluyó el fragmento (por ejemplo providers.services.create)
				// Si no está seteada, podemos construirla a partir del provider_id oculto:
				if (!form.getAttribute('action') || form.getAttribute('action') === '') {
					const pid = hiddenProv.value;
					if (pid) form.setAttribute('action', '{{ url('providers') }}/' + encodeURIComponent(pid) + '/services');
				}
			});
		}

		// Si el servidor devolvió errores (validación), reabrir modal y mostrar errores
		try {
			@if($errors->any())
				setTimeout(function(){
					// mostrar errores del servidor en los divs correspondientes
					@foreach(['provider_id'=>'create_service_provider_error','name'=>'create_service_name_error','duration_minutes'=>'create_service_duration_error','price'=>'create_service_price_error'] as $field => $target)
						@if($errors->first($field))
							(function(){ const el=document.getElementById('{{ $target }}'); if(el){ el.textContent = {!! json_encode($errors->first($field)) !!}; el.style.display='block'; } })();
						@endif
					@endforeach
					// si hubo provider_id viejo, setearlo en el select (ya lo hace old(...) en el option) y abrir modal
					const createForm = document.getElementById('createServiceForm');
					if (createForm) {
						// si viene provider_id como campo oculto, no hacemos nada más
						modal.style.display = 'flex';
						try { createForm.querySelector('input[name="name"]').focus(); } catch(e){}
					}
				}, 60);
			@endif
		} catch(e){ /* defensivo */ }

	})();
</script>
