<dialog id="viewDialog" aria-labelledby="viewDialogTitle" class="dialog" style="padding:0;border-radius:10px;border:0;max-width:640px;width:92%;">
	<form method="dialog" style="display:block;">
		<header style="display:flex;justify-content:space-between;align-items:center;padding:18px 18px 0 18px">
			<h3 id="viewDialogTitle" style="margin:0;font-size:18px">Proveedor</h3>
			<button type="button" id="viewClose" aria-label="Cerrar" style="background:transparent;border:0;font-size:20px;cursor:pointer;color:#6b7280">&times;</button>
		</header>

		<main id="viewDialogContent" style="padding:12px 18px 6px;color:#374151;font-size:14px;display:flex;flex-direction:column;gap:10px;">
			<!-- campos básicos -->
			<div><strong>Nombre:</strong> <span data-field="name">--</span></div>
			<div><strong>Email:</strong> <span data-field="email">--</span></div>
			<div><strong>Teléfono:</strong> <span data-field="phone">--</span></div>
			<div><strong>Creado:</strong> <span data-field="created">--</span></div>

			<!-- servicios: se rellenan dinámicamente -->
			<section id="servicesSection" style="margin-top:6px">
				<strong>Servicios:</strong>
				<div id="servicesContainer" style="margin-top:8px;color:#374151;font-size:14px;">
					<span id="servicesLoading" style="display:none;color:#6b7280">Cargando servicios…</span>
					<ul id="servicesList" style="margin:6px 0 0 18px;padding:0;list-style:disc"></ul>
					<div id="servicesEmpty" style="display:none;color:var(--muted)">No hay servicios disponibles.</div>
				</div>
			</section>
		</main>

		<footer style="display:flex;justify-content:flex-end;padding:0 18px 18px;">
			<button id="viewCloseFooter" type="button" style="padding:8px 12px;border-radius:8px;background:#e5e7eb;border:0;cursor:pointer">Cerrar</button>
		</footer>
	</form>
</dialog>

<template id="viewTemplate">
	<!-- plantilla usada si se quiere clonar (no estrictamente necesaria con el enfoque actual) -->
	<div><strong>Nombre:</strong> <span data-field="name">--</span></div>
	<div><strong>Email:</strong> <span data-field="email">--</span></div>
	<div><strong>Teléfono:</strong> <span data-field="phone">--</span></div>
	<div><strong>Creado:</strong> <span data-field="created">--</span></div>
</template>

<script>
	(function () {
		const dialog = document.getElementById('viewDialog');
		const content = document.getElementById('viewDialogContent');
		const closeBtn = document.getElementById('viewClose');
		const closeFooter = document.getElementById('viewCloseFooter');

		const servicesList = document.getElementById('servicesList');
		const servicesLoading = document.getElementById('servicesLoading');
		const servicesEmpty = document.getElementById('servicesEmpty');

		let lastFocused = null;

		function resetServicesUI() {
			servicesList.innerHTML = '';
			servicesLoading.style.display = 'none';
			servicesEmpty.style.display = 'none';
		}

		function renderServicesArray(arr) {
			resetServicesUI();
			if (!Array.isArray(arr) || arr.length === 0) {
				servicesEmpty.style.display = '';
				return;
			}
			const frag = document.createDocumentFragment();
			arr.forEach(s => {
				const li = document.createElement('li');
				// mostrar nombre y opcionales (description/price) si existen
				let text = s.name ?? s.title ?? 'Servicio';
				if (s.description) text += ' — ' + s.description;

				// formatear precio con símbolo $ (si no viene ya con uno)
				if (s.price !== undefined && s.price !== null) {
					try {
						const raw = String(s.price).trim();
						let display;
						if (raw.startsWith('$')) {
							display = raw;
						} else if (!isNaN(Number(raw))) {
							display = '$' + new Intl.NumberFormat(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 2 }).format(Number(raw));
						} else {
							display = '$' + raw;
						}
						text += ' (' + display + ')';
					} catch (e) {
						// fallback simple
						text += ' ($' + String(s.price) + ')';
					}
				}

				li.textContent = text;
				frag.appendChild(li);
			});
			servicesList.appendChild(frag);
		}

		async function fetchServicesById(id) {
			resetServicesUI();
			servicesLoading.style.display = '';
			try {
				const res = await fetch('/providers/' + encodeURIComponent(id) + '/services', { credentials: 'same-origin' });
				if (!res.ok) throw new Error('Network response not ok');
				const data = await res.json();
				renderServicesArray(data);
			} catch (e) {
				// en caso de error, mostrar mensaje vacío
				resetServicesUI();
				servicesEmpty.textContent = 'No se pudieron cargar los servicios.';
				servicesEmpty.style.display = '';
			} finally {
				servicesLoading.style.display = 'none';
			}
		}

		// populate fields and services. target is the element that triggered the modal (.btn-view)
		function populate(target) {
			// campos simples (name,email,phone,created)
			['name','email','phone','created'].forEach(k => {
				const el = content.querySelector('[data-field="'+k+'"]');
				if (el) el.textContent = target.dataset[k] ?? '--';
			});

			// manejar servicios:
			resetServicesUI();
			// 1) si el disparador trae data-services (JSON), procesarlo directamente
			if (target.dataset.services) {
				try {
					const parsed = JSON.parse(target.dataset.services);
					renderServicesArray(parsed);
					return;
				} catch (e) {
					// si falla el parse, continuar y/o intentar fetch por id
				}
			}
			// 2) si hay data-id, intentar fetch '/providers/{id}/services'
			if (target.dataset.id) {
				fetchServicesById(target.dataset.id);
				return;
			}
			// 3) fallback: no hay datos
			servicesEmpty.textContent = 'Servicios no disponibles.';
			servicesEmpty.style.display = '';
		}

		function openDialog() {
			lastFocused = document.activeElement;
			if (typeof dialog.showModal === 'function') {
				try { dialog.showModal(); } catch(e){ dialog.setAttribute('open',''); }
			} else {
				dialog.setAttribute('open', '');
				dialog.style.position = 'fixed';
				dialog.style.left = '50%';
				dialog.style.top = '50%';
				dialog.style.transform = 'translate(-50%,-50%)';
				dialog.style.zIndex = 10001;
			}
			const f = dialog.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
			if (f) f.focus();
		}

		function closeDialog() {
			if (typeof dialog.close === 'function' && dialog.open) {
				dialog.close();
			} else {
				dialog.removeAttribute('open');
				dialog.style.transform = '';
				dialog.style.position = '';
				dialog.style.left = '';
				dialog.style.top = '';
				dialog.style.zIndex = '';
			}
			if (lastFocused) lastFocused.focus();
		}

		// Delegación: cualquier .btn-view abre el modal usando sus data-* atributos
		document.addEventListener('click', function (e) {
			const btn = e.target.closest('.btn-view');
			if (!btn) return;
			e.preventDefault();
			populate(btn);
			openDialog();
		});

		// handlers de cierre
		closeBtn.addEventListener('click', closeDialog);
		closeFooter.addEventListener('click', closeDialog);

		// cierre con esc y click fuera
		dialog.addEventListener('cancel', function (ev) { ev.preventDefault(); closeDialog(); });
		dialog.addEventListener('click', function (ev) { if (ev.target === dialog) closeDialog(); });

		// trap básico de foco
		dialog.addEventListener('keydown', function (ev) {
			if (ev.key === 'Escape') return closeDialog();
			if (ev.key !== 'Tab') return;
			const focusables = Array.from(dialog.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'))
				.filter(el => !el.hasAttribute('disabled'));
			if (!focusables.length) return;
			const first = focusables[0];
			const last = focusables[focusables.length - 1];
			if (ev.shiftKey && document.activeElement === first) {
				ev.preventDefault(); last.focus();
			} else if (!ev.shiftKey && document.activeElement === last) {
				ev.preventDefault(); first.focus();
			}
		});
	})();
</script>
