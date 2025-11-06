<aside class="sidebar" role="navigation" aria-label="Main sidebar">
	<div style="display:flex;flex-direction:column;height:100%;padding-bottom:12px;box-sizing:border-box;">
		<div>
			<h3 style="margin-top:0">SIDEBAR</h3>
            <BR></BR>
			<nav>
				<a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
					<!-- icon home (casita) -->
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" style="vertical-align:middle;margin-right:8px" aria-hidden="true">
						<path d="M3 11.5L12 4l9 7.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
						<path d="M5 11.5v7.5a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1v-7.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
					</svg>
					Menú
				</a>

				<a href="{{ route('providers.index') }}" class="{{ request()->routeIs('providers.*') ? 'active' : '' }}">
					<!-- icon providers -->
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" style="vertical-align:middle;margin-right:8px">
						<circle cx="9" cy="8" r="3" stroke="currentColor" stroke-width="1.5" fill="none"/>
						<path d="M3 20c1-4 5-6 9-6s8 2 9 6" stroke="currentColor" stroke-width="1.5" fill="none"/>
					</svg>
					Proveedores
				</a>

				<a href="{{ route('services.index') }}" class="{{ (request()->routeIs('services.*') || request()->routeIs('providers.services.*')) ? 'active' : '' }}">
					<!-- icon services -->
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" style="vertical-align:middle;margin-right:8px">
						<path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.5" fill="none"/>
					</svg>
					Servicios
				</a>
			</nav>
		</div>

		<hr style="border:none;height:1px;background:rgba(255,255,255,0.06);margin:14px 0">

		<!-- logout al fondo -->
		<div style="margin-top:auto;">
			<form method="POST" action="{{ route('logout') }}">
				@csrf
				<button type="submit" style="display:flex;align-items:center;gap:8px;padding:10px;border-radius:8px;background:#ef4444;border:0;color:#fff;cursor:pointer;width:100%;font-weight:600">
					<!-- icon logout -->
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" style="vertical-align:middle" aria-hidden="true">
						<path d="M16 17l5-5-5-5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
						<path d="M21 12H9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
						<path d="M13 19H6a2 2 0 01-2-2V7a2 2 0 012-2h7" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
					</svg>
					Cerrar sesión
				</button>
			</form>
		</div>
	</div>
</aside>