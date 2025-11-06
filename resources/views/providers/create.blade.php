<!-- Partial: create modal + JS (sin extender layout; incluir desde index) -->
<div id="createModal" style="display:none;position:fixed;inset:0;z-index:10002;align-items:center;justify-content:center;background:rgba(0,0,0,0.45);">
	<div role="dialog" aria-modal="true" aria-labelledby="createModalTitle"
	     style="background:#fff;padding:18px;border-radius:10px;max-width:520px;width:94%;box-shadow:0 8px 30px rgba(2,6,23,0.2);max-height:calc(100vh - 80px);overflow:auto;box-sizing:border-box;">
		<h3 id="createModalTitle" style="margin:0 0 12px 0;font-size:18px">Nuevo proveedor</h3>

		<!-- novalidate: usamos validación JS personalizada -->
		<form id="createForm" method="POST" novalidate style="display:flex;flex-direction:column;gap:10px">
			@csrf
			<!-- preserve action so old('_action') is available after redirect on validation error -->
			<input type="hidden" name="_action" value="{{ old('_action','') }}">

			<label style="display:block;font-size:13px;color:#374151;">
				Nombre
				<input name="name" id="create_name" type="text" required value="{{ old('name') }}" style="margin-top:6px;width:100%;padding:10px;border-radius:8px;border:1px solid #e5e7eb;font-size:14px;box-sizing:border-box;display:block" />
				<div id="create_name_error" style="color:#dc2626;font-size:13px;margin-top:6px;display:none"></div>
			</label>

			<label style="display:block;font-size:13px;color:#374151;">
				Email
				<input name="email" id="create_email" type="email" required value="{{ old('email') }}" style="margin-top:6px;width:100%;padding:10px;border-radius:8px;border:1px solid #e5e7eb;font-size:14px;box-sizing:border-box;display:block" />
				<div id="create_email_error" style="color:#dc2626;font-size:13px;margin-top:6px;display:none"></div>
			</label>

			<label style="display:block;font-size:13px;color:#374151;">
				Teléfono
				<input name="phone" id="create_phone" type="text" required value="{{ old('phone') }}" style="margin-top:6px;width:100%;padding:10px;border-radius:8px;border:1px solid #e5e7eb;font-size:14px;box-sizing:border-box;display:block" />
				<div id="create_phone_error" style="color:#dc2626;font-size:13px;margin-top:6px;display:none"></div>
			</label>

			<div style="display:flex;justify-content:flex-end;gap:8px;margin-top:6px">
				<button type="button" id="cancelCreate" style="padding:8px 12px;border-radius:8px;background:#f3f4f6;border:0;cursor:pointer">Cancelar</button>
				<button type="submit" id="saveCreate" style="padding:8px 12px;border-radius:8px;background:#059669;color:#fff;border:0;cursor:pointer">Crear</button>
			</div>
		</form>
	</div>
</div>

<script>
	// JS autocontenido para el modal "Crear"
	(function(){
		const createModal = document.getElementById('createModal');
		const createForm = document.getElementById('createForm');
		const btnNew = document.querySelector('.btn-new');
		const cancelCreate = document.getElementById('cancelCreate');

		// helper validation
		function validateCreate() {
			const nameEl = document.getElementById('create_name');
			const emailEl = document.getElementById('create_email');
			const phoneEl = document.getElementById('create_phone');
			const nameErr = document.getElementById('create_name_error');
			const emailErr = document.getElementById('create_email_error');
			const phoneErr = document.getElementById('create_phone_error');

			[nameErr,emailErr,phoneErr].forEach(el=>{ if(el){ el.textContent=''; el.style.display='none'; } });

			const name = (nameEl?.value || '').trim();
			const email = (emailEl?.value || '').trim();
			const phone = (phoneEl?.value || '').trim();

			const emailOk = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
			const phoneOk = /^\+?[0-9\s\-()]{7,}$/.test(phone);

			let firstInvalid = null;
			if (!name) { if(nameErr){ nameErr.textContent='El nombre es obligatorio.'; nameErr.style.display='block'; } firstInvalid = firstInvalid || nameEl; }
			if (!email) { if(emailErr){ emailErr.textContent='El email es obligatorio.'; emailErr.style.display='block'; } firstInvalid = firstInvalid || emailEl; }
			else if (!emailOk) { if(emailErr){ emailErr.textContent='Email no válido.'; emailErr.style.display='block'; } firstInvalid = firstInvalid || emailEl; }
			if (!phone) { if(phoneErr){ phoneErr.textContent='El teléfono es obligatorio.'; phoneErr.style.display='block'; } firstInvalid = firstInvalid || phoneEl; }
			else if (!phoneOk) { if(phoneErr){ phoneErr.textContent='Teléfono no válido.'; phoneErr.style.display='block'; } firstInvalid = firstInvalid || phoneEl; }

			return firstInvalid;
		}

		if (btnNew) {
			btnNew.addEventListener('click', function(){
				const action = this.getAttribute('data-action');
				createForm.setAttribute('action', action);
				// set hidden _action so it will be submitted and available via old('_action') on redirect
				const actionInput = createForm.querySelector('input[name="_action"]');
				if (actionInput) actionInput.value = action;

				// clear fields
				createForm.querySelector('input[name="name"]').value = '';
				createForm.querySelector('input[name="email"]').value = '';
				createForm.querySelector('input[name="phone"]').value = '';
				// hide previous errors
				['create_name_error','create_email_error','create_phone_error'].forEach(id=>{ const el=document.getElementById(id); if(el) el.style.display='none'; });
				createModal.style.display = 'flex';
				setTimeout(()=> createForm.querySelector('input[name="name"]').focus(), 40);
			});
		}

		if (cancelCreate) {
			cancelCreate.addEventListener('click', function(){ createModal.style.display = 'none'; });
		}
		createModal.addEventListener('click', function(e){ if (e.target === createModal) createModal.style.display = 'none'; });

		// Validation on submit: all fields required + simple email/phone format
		if (createForm) {
			createForm.addEventListener('submit', function(e){
				const firstInvalid = validateCreate();
				if (firstInvalid) {
					e.preventDefault();
					firstInvalid.focus();
				}
				// otherwise allow submit
			});
		}

		// Si el servidor regresó con errores de validación tras intentar CREAR, abrir el modal al cargar
		@if($errors->any() && old('_method') !== 'PUT')
			// execute after current script finishes to ensure DOM ready
			setTimeout(function(){
				const formAction = '{{ old("_action") ?: "" }}';
				if (formAction && createForm) createForm.setAttribute('action', formAction);
				if (createModal) createModal.style.display = 'flex';

				// Mostrar mensajes de error del servidor en los divs correspondientes
				(function(){
					try {
						const nameErr = {!! json_encode($errors->first('name')) !!};
						const emailErr = {!! json_encode($errors->first('email')) !!};
						const phoneErr = {!! json_encode($errors->first('phone')) !!};

						if (nameErr) {
							const el = document.getElementById('create_name_error');
							if (el) { el.textContent = nameErr; el.style.display = 'block'; }
						}
						if (emailErr) {
							const el = document.getElementById('create_email_error');
							if (el) { el.textContent = emailErr; el.style.display = 'block'; }
						}
						if (phoneErr) {
							const el = document.getElementById('create_phone_error');
							if (el) { el.textContent = phoneErr; el.style.display = 'block'; }
						}
					} catch(e){}
				})();

				try { createForm.querySelector('input[name="name"]').focus(); } catch(e){}
			}, 60);
		@endif
	})();
</script>
