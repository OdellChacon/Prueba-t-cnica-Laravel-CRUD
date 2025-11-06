<script>
	(function(){
		const input = document.getElementById('searchInput');
		const table = document.querySelector('table');
		if (!input || !table) return;

		function debounce(fn, ms=300){
			let t;
			return (...a) => { clearTimeout(t); t = setTimeout(()=> fn.apply(this, a), ms); };
		}

		// cache: array of tr outerHTML
		let allRows = null;
		let fetching = false;

		async function fetchPageDoc(page=1){
			const url = new URL(window.location.href);
			url.searchParams.delete('q'); // get raw pages
			url.searchParams.set('page', page);
			const res = await fetch(url.toString(), { headers:{ 'X-Requested-With':'XMLHttpRequest' }, credentials:'same-origin' });
			if (!res.ok) throw new Error('HTTP ' + res.status);
			const txt = await res.text();
			return new DOMParser().parseFromString(txt, 'text/html');
		}

		function rowsFromDoc(doc){
			return Array.from(doc.querySelectorAll('table tbody tr')).map(tr => tr.outerHTML);
		}

		function detectLastPage(doc){
			let last = 1;
			doc.querySelectorAll('a[href]').forEach(a=>{
				try {
					const u = new URL(a.href);
					const p = parseInt(u.searchParams.get('page')||'1',10);
					if (!isNaN(p) && p > last) last = p;
				} catch(e){}
			});
			return last;
		}

		async function ensureAllRows(){
			if (allRows !== null || fetching) return;
			fetching = true;
			try {
				const firstDoc = await fetchPageDoc(1);
				const rows = rowsFromDoc(firstDoc);
				const last = detectLastPage(firstDoc);
				if (last > 1){
					const promises = [];
					for (let p = 2; p <= last; p++) promises.push(fetchPageDoc(p));
					const docs = await Promise.all(promises);
					docs.forEach(d => rows.push(...rowsFromDoc(d)));
				}
				allRows = rows;
			} catch(e) {
				console.error('Error cargando páginas para búsqueda:', e);
				allRows = [];
			} finally {
				fetching = false;
			}
		}

		function renderRows(rowsHtml){
			const oldTbody = document.querySelector('table tbody');
			const newTbody = document.createElement('tbody');
			newTbody.innerHTML = rowsHtml.length ? rowsHtml.join('') : '<tr><td colspan="5" style="padding:12px;text-align:center;color:var(--muted)">No hay servicios.</td></tr>';
			if (oldTbody && oldTbody.parentElement) oldTbody.parentElement.replaceChild(newTbody, oldTbody);
		}

		function filterCache(q){
			const qn = (q || '').trim().toLowerCase();
			if (!qn) return null;
			return allRows.filter(trHtml => {
				const tmp = document.createElement('tbody');
				tmp.innerHTML = trHtml;
				const tr = tmp.querySelector('tr');
				if (!tr) return false;
				const tds = tr.querySelectorAll('td');
				const svc = (tds[0]?.textContent || '').toLowerCase();
				const prov = (tds[3]?.textContent || '').toLowerCase();
				return svc.indexOf(qn) !== -1 || prov.indexOf(qn) !== -1;
			});
		}

		function hidePagination(hide){
			const pag = document.querySelector('div[style*="margin-top:12px;display:flex;justify-content:center;"]');
			if (pag) pag.style.display = hide ? 'none' : '';
		}

		const onInput = debounce(async function(){
			const q = input.value;
			if (!q) {
				// restore by reloading current view to get pagination & original page content
				// (keeps behavior simple and consistent)
				window.location.href = window.location.pathname + (window.location.search ? window.location.search : '');
				return;
			}
			await ensureAllRows();
			const filtered = filterCache(q) || [];
			renderRows(filtered);
			hidePagination(true);
		}, 300);

		input.addEventListener('keydown', e => { if (e.key === 'Enter') e.preventDefault(); });
		input.addEventListener('input', onInput);

		// if page loaded with q present, perform full search (so results come from server-side initial filtering)
		if (input.value) {
			// try to fetch full cache and refine if needed
			ensureAllRows().then(()=>{
				const filtered = filterCache(input.value) || [];
				if (filtered.length) {
					renderRows(filtered);
					hidePagination(true);
				}
			});
		}
	})();
</script>
