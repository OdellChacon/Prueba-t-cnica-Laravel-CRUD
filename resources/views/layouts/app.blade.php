<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield('title', 'App')</title>
	<link rel="preconnect" href="https://fonts.bunny.net">
	<link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
	<style>
		:root{ --accent:#ef4444; --muted:#6b7280; --card-bg:#fff; --radius:10px; --shadow:0 8px 30px rgba(2,6,23,0.08); font-family:"Figtree",system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial; }
		html,body{height:100%;margin:0;background:#f3f4f6;color:#0b1220}
		.app{display:flex;min-height:100vh;align-items:stretch}
		.sidebar{width:260px;background:#0b1220;color:#fff;padding:20px;box-sizing:border-box}
		.sidebar h3{margin:0 0 12px 0;font-size:18px}
		.sidebar nav a{display:block;color:rgba(255,255,255,0.92);text-decoration:none;padding:10px;border-radius:8px;margin-bottom:6px}
		.sidebar nav a.active{background:rgba(255,255,255,0.06)}
		.main{flex:1;padding:28px;box-sizing:border-box}
		.topbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px}
		.card{background:var(--card-bg);padding:20px;border-radius:var(--radius);box-shadow:var(--shadow)}
		.user-info{font-size:14px;color:var(--muted)}
		.logout-form{display:inline}
		.btn-logout{background:var(--accent);color:#fff;border:0;padding:8px 10px;border-radius:8px;cursor:pointer}
		@media (max-width:800px){ .sidebar{display:none} .app{flex-direction:column} .main{padding:16px} }
	</style>
</head>
<body>
	<div class="app">
		@include('partials.sidebar')
		<main class="main">
			<div class="topbar">
				<div>
					<h2 style="margin:0">@yield('title', 'App')</h2>
					<p class="user-info">@yield('subtitle')</p>
				</div>
				<div>
					@if(Auth::check())
						<span style="margin-right:12px">Hola, {{ Auth::user()->name ?? Auth::user()->email }}</span>
						<form method="POST" action="{{ route('logout') }}" class="logout-form">
							@csrf
						</form>
					@endif
				</div>
			</div>

			<section>
				@yield('content')
			</section>
		</main>
	</div>
</body>
</html>
