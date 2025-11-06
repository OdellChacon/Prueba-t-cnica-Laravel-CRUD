<!-- Partial: edit modal + JS (sin extender layout; incluir desde index) -->
<div id="editModal" style="display:none;position:fixed;inset:0;z-index:10000;align-items:center;justify-content:center;background:rgba(0,0,0,0.45);">
	<div role="dialog" aria-modal="true" aria-labelledby="editModalTitle"
	     style="background:#fff;padding:18px;border-radius:10px;max-width:520px;width:94%;box-shadow:0 8px 30px rgba(2,6,23,0.2);max-height:calc(100vh - 80px);overflow:auto;box-sizing:border-box;">
		<h3 id="editModalTitle" style="margin:0 0 12px 0;font-size:18px">Editar proveedor</h3>

		<!-- Nota: el atributo action se establece desde JS usando data-action del botón .btn-edit -->
		<!-- novalidate: usamos validación JS personalizada -->
		<form id="editForm" method="POST" novalidate style="display:flex;flex-direction:column;gap:10px">
			@csrf
			@method('PUT')

			<!-- preserve provider id so we can rebuild action on validation failure -->
			<input type="hidden" name="id" value="{{ old('id','') }}">
			<!-- preserve action so old('_action') is available after redirect on validation error -->
			<input type="hidden" name="_action" value="{{ old('_action','') }}">

			<label style="display:block;font-size:13px;color:#374151;word-break:break-word;white-space:normal;">
				Nombre
				<input name="name" id="edit_name" type="text" required style="margin-top:6px;width:100%;padding:10px;border-radius:8px;border:1px solid #e5e7eb;font-size:14px;box-sizing:border-box;display:block;" />
				<div id="edit_name_error" style="color:#dc2626;font-size:13px;margin-top:6px;display:none"></div>
			</label>

			<label style="display:block;font-size:13px;color:#374151;word-break:break-word;white-space:normal;">
				Email
				<input name="email" id="edit_email" type="email" required style="margin-top:6px;width:100%;padding:10px;border-radius:8px;border:1px solid #e5e7eb;font-size:14px;box-sizing:border-box;display:block;" />
				<div id="edit_email_error" style="color:#dc2626;font-size:13px;margin-top:6px;display:none"></div>
			</label>

			<label style="display:block;font-size:13px;color:#374151;word-break:break-word;white-space:normal;">
				Teléfono
				<input name="phone" id="edit_phone" type="text" required style="margin-top:6px;width:100%;padding:10px;border-radius:8px;border:1px solid #e5e7eb;font-size:14px;box-sizing:border-box;display:block;" />
				<div id="edit_phone_error" style="color:#dc2626;font-size:13px;margin-top:6px;display:none"></div>
			</label>

			<div style="display:flex;justify-content:flex-end;gap:8px;margin-top:6px">
				<button type="button" id="cancelEdit" style="padding:8px 12px;border-radius:8px;background:#f3f4f6;border:0;cursor:pointer">Cancelar</button>
				<button type="submit" id="saveEdit" style="padding:8px 12px;border-radius:8px;background:#059669;color:#fff;border:0;cursor:pointer">Guardar</button>
			</div>
		</form>
	</div>
</div>

<script>
	// JS autocontenido para el modal "Editar"
	(function(){
		const editModal = document.getElementById('editModal');
		const editForm = document.getElementById('editForm');
		const cancelEdit = document.getElementById('cancelEdit');

		function validateEdit() {
			const nameEl = document.getElementById('edit_name');
			const emailEl = document.getElementById('edit_email');
			const phoneEl = document.getElementById('edit_phone');
			const nameErr = document.getElementById('edit_name_error');
			const emailErr = document.getElementById('edit_email_error');
			const phoneErr = document.getElementById('edit_phone_error');

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

		document.querySelectorAll('.btn-edit').forEach(btn => {
			btn.addEventListener('click', function(e){
				e.preventDefault();
				const action = this.getAttribute('data-action') || '';
				const id = this.getAttribute('data-id') || '';
				const name = this.getAttribute('data-name') || '';
				const email = this.getAttribute('data-email') || '';
				const phone = this.getAttribute('data-phone') || '';

				// set action and values
				if (action) {
					editForm.setAttribute('action', action);
					// also set hidden _action so it is submitted/preserved if validation fails
					const actionInput = editForm.querySelector('input[name="_action"]');
					if (actionInput) actionInput.value = action;
				}
				// set hidden id for server-side handling and for fallback logic
				const idInput = editForm.querySelector('input[name="id"]');
				if (idInput) idInput.value = id;

				editForm.querySelector('input[name="name"]').value = name;
				editForm.querySelector('input[name="email"]').value = email;
				editForm.querySelector('input[name="phone"]').value = phone;

				// hide previous errors
				['edit_name_error','edit_email_error','edit_phone_error'].forEach(id=>{ const el=document.getElementById(id); if(el) el.style.display='none'; });

				editModal.style.display = 'flex';
				setTimeout(()=> editForm.querySelector('input[name="name"]').focus(), 50);
			});
		});

		if (cancelEdit) {
			cancelEdit.addEventListener('click', function(){ editModal.style.display = 'none'; });
		}
		editModal.addEventListener('click', function(e){ if (e.target === editModal) editModal.style.display = 'none'; });

		// Validation on submit: all fields required + simple email/phone format
		if (editForm) {
			editForm.addEventListener('submit', function(e){
				const firstInvalid = validateEdit();
				if (firstInvalid) {
					e.preventDefault();
					firstInvalid.focus();
				}
			});
		}
	})();
</script>

{{-- Si la validación del servidor falló al hacer PUT, reabrir modal editar y poblar campos --}}
@if($errors->any() && old('_method') === 'PUT')
	<script>
		// ejecutar después de que el DOM y los scripts del partial hayan corrido
		setTimeout(function(){
			const editForm = document.getElementById('editForm');
			const editModal = document.getElementById('editModal');
			if (!editForm || !editModal) return;

			let editAction = '{{ old("_action") ?: "" }}';
			// fallback: if no action provided, try to rebuild from baseProvidersUrl + id
			const oldEditId = '{{ old("id","") }}';
			if ((!editAction || editAction === '') && oldEditId) {
				editAction = '{{ url("providers") }}' + '/' + oldEditId;
			}
			if (editAction) {
				try { editForm.setAttribute('action', editAction); } catch(e){}
			}
			// populate values from old() if needed
			try {
				const nameInput = editForm.querySelector('input[name="name"]');
				const emailInput = editForm.querySelector('input[name="email"]');
				const phoneInput = editForm.querySelector('input[name="phone"]');
				if (nameInput) nameInput.value = '{{ addslashes(old("name","")) }}';
				if (emailInput) emailInput.value = '{{ addslashes(old("email","")) }}';
				if (phoneInput) phoneInput.value = '{{ addslashes(old("phone","")) }}';
				// ensure hidden id is set
				const idInput = editForm.querySelector('input[name="id"]');
				if (idInput) idInput.value = oldEditId;
			} catch(e){}
			
			// Mostrar mensajes de error del servidor en los divs correspondientes
			(function(){
				try {
					const nameErr = {!! json_encode($errors->first('name')) !!};
					const emailErr = {!! json_encode($errors->first('email')) !!};
					const phoneErr = {!! json_encode($errors->first('phone')) !!};

					if (nameErr) {
						const el = document.getElementById('edit_name_error');
						if (el) { el.textContent = nameErr; el.style.display = 'block'; }
					}
					if (emailErr) {
						const el = document.getElementById('edit_email_error');
						if (el) { el.textContent = emailErr; el.style.display = 'block'; }
					}
					if (phoneErr) {
						const el = document.getElementById('edit_phone_error');
						if (el) { el.textContent = phoneErr; el.style.display = 'block'; }
					}
				} catch(e){}
			})();

			editModal.style.display = 'flex';
			try { editForm.querySelector('input[name="name"]').focus(); } catch(e){}
		}, 60);
	</script>
@endif
