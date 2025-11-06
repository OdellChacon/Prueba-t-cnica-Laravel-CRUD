<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Prueba con Laravel</title>

	<!-- CSRF token for JS/AJAX and diagnostics -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<!-- Fonts -->
	<link rel="preconnect" href="https://fonts.bunny.net">
	<link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

	<!-- Styles: diseño propio, ligero y sin frameworks -->
	<style>
		:root{
			--bg-1: #0f172a;
			--bg-2: #0b1220;
			--card: #ffffff;
			--muted: #6b7280;
			--accent: #ef4444;
			--radius: 12px;
			--glass: rgba(255,255,255,0.06);
			--shadow: 0 10px 30px rgba(2,6,23,0.6);
			--max-width: 420px;
			font-family: "Figtree", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
		}
		html,body {height:100%;margin:0;}
		body{
			background: linear-gradient(135deg,var(--bg-1) 0%, var(--bg-2) 100%);
			color:#0b1220;
			-webkit-font-smoothing:antialiased;
			-moz-osx-font-smoothing:grayscale;
			display:flex;
			align-items:center;
			justify-content:center;
			padding:24px;
		}

		.container{
			width:100%;
			max-width:1100px;
			display:grid;
			grid-template-columns:1fr;
			gap:32px;
			align-items:center;
		}

		.header{
			display:flex;
			justify-content:center;
			align-items:center;
			gap:16px;
		}

		.logo{
			width:56px;height:56px;border-radius:10px;background:#fff;display:flex;align-items:center;justify-content:center;box-shadow:0 6px 20px rgba(0,0,0,0.25);
		}
		.logo svg{width:32px;height:32px}

		.card{
			background: linear-gradient(180deg, rgba(255,255,255,0.95), rgba(255,255,255,0.94));
			border-radius:var(--radius);
			padding:28px;
			box-shadow:var(--shadow);
			max-width:var(--max-width);
			margin:0 auto;
		}

		.title{font-size:20px;font-weight:600;color:#0f172a;margin:0; text-align:center;}
		.subtitle{font-size:13px;color:var(--muted);margin-top:6px;margin-bottom:18px; text-align:center;}

		form .field{margin-bottom:14px;}
		.field label{display:block;font-size:13px;color:#374151;margin-bottom:8px;}
		.input{
			width:100%;
			display:flex;
			align-items:center;
			gap:10px;
			padding:10px 12px;
			border-radius:10px;
			background:#f8fafc;
			border:1px solid #e6edf3;
			box-sizing:border-box;
		}
		.input input{
			border:0;background:transparent;outline:none;font-size:15px;width:100%;
		}
		.input .icon{width:18px;height:18px;color:#94a3b8}

		/* --- ADICIONES: estilos para estado inválido, medidor y spinner --- */
		.input.invalid{ border-color:#ef4444; box-shadow:0 6px 20px rgba(239,68,68,0.06); }
		.strength{ height:8px; border-radius:8px; background:#eef2f6; overflow:hidden; margin-top:8px; }
		.strength > i{ display:block; height:100%; width:0%; transition:width .25s ease, background .25s ease; background:linear-gradient(90deg,#f97316,#ef4444); }
		.strength-label{ font-size:12px;color:var(--muted); margin-top:6px; }
		.btn .spinner{ width:14px; height:14px; border:2px solid rgba(255,255,255,0.4); border-top-color:#fff; border-radius:50%; animation:spin .8s linear infinite; display:inline-block; margin-left:10px; vertical-align:middle; }
		@keyframes spin{ to{ transform:rotate(360deg);} }

		.helper{font-size:12px;color:var(--muted);margin-top:6px;}
		.error{font-size:13px;color:#b91c1c;margin-top:6px;}

		.row{display:flex;align-items:center;justify-content:space-between;margin-top:6px}
		.checkbox{display:flex;align-items:center;gap:8px;font-size:14px;color:#374151}
		.link{font-size:14px;color:var(--accent);text-decoration:underline;cursor:pointer}

		.btn{
			display:inline-flex;
			align-items:center;
			justify-content:center;
			width:100%;
			padding:12px 16px;
			border-radius:10px;
			background:var(--accent);
			color:#fff;
			border:0;
			font-weight:600;
			font-size:15px;
			cursor:pointer;
			box-shadow: 0 8px 20px rgba(239,68,68,0.18);
		}
		.footer{
			text-align:center;color:#cbd5e1;font-size:13px;margin-top:18px;
		}

		/* responsive */
		@media (min-width:900px){
			.container{grid-template-columns: 1fr 420px;align-items:center}
			.header{justify-content:flex-start}
		}
	</style>
</head>
<body>
	<div class="container">
		<!-- left: info / welcome -->
		<div style="color:#fff; padding:20px;">
			<div class="header">
				<div class="logo" aria-hidden="true">
					<!-- simple svg -->
					<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
						<path d="M12 2L2 7v6c0 5 5 9 10 9s10-4 10-9V7L12 2z" fill="#FF2D20"/>
					</svg>
				</div>
				<div>
					<h2 style="margin:0;color:#fff;font-size:18px;font-weight:700;">Prueba con Laravel</h2>
					<p style="margin:2px 0 0 0;color:rgba(255,255,255,0.85)">Primera vez haciendo una aplicacion con Laravel</p>
				</div>
			</div>
		</div>

		<!-- right: card with form -->
		<div class="card" role="main" aria-labelledby="loginTitle">
			<h1 id="loginTitle" class="title">Iniciar sesión</h1>
			<p class="subtitle">Introduce tus credenciales para acceder.</p>

			@if ($errors->any())
				<div class="error" role="alert">
					<ul style="margin:0;padding-left:18px;">
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif

			<!-- REEMPLAZADO: formulario mejorado con medidor y validación cliente -->
			<form method="POST" action="{{ route('login') }}" style="margin-top:14px" novalidate id="loginForm">
				@csrf

				<!-- email -->
				<div class="field">
					<label for="email">Email</label>
					<div class="input" role="group" aria-labelledby="email" id="emailWrap">
						<span class="icon" aria-hidden="true">
							<!-- nuevo icono: sobre/envelope -->
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" width="18" height="18" aria-hidden="true">
								<rect x="2" y="5" width="20" height="14" rx="2" stroke="#94a3b8" stroke-width="1.6" fill="none"/>
								<path d="M3 7.5l9 6 9-6" stroke="#94a3b8" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
							</svg>
						</span>
						<input id="email" name="email" type="email" placeholder="textodeejemplo@gmail.com" value="{{ old('email') }}" required aria-describedby="{{ $errors->has('email') ? 'email-error' : 'email-client-error' }}" autocomplete="email" inputmode="email" />
					</div>

					<!-- mensaje de error cliente -->
					<p id="email-client-error" class="error" style="display:none" aria-live="polite"></p>

					@if ($errors->has('email'))
						<p id="email-error" class="error">{{ $errors->first('email') }}</p>
					@else
						<p class="helper">Usa el email con el que te registraste.</p>
					@endif
				</div>

				<!-- password -->
				<div class="field">
					<label for="password">Contraseña</label>
					<div class="input" style="position:relative;" id="pwdWrap">
						<span class="icon" aria-hidden="true">
							<!-- nuevo icono: candado/lock -->
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" width="18" height="18" aria-hidden="true">
								<rect x="3" y="11" width="18" height="10" rx="2" stroke="#94a3b8" stroke-width="1.6" fill="none"/>
								<path d="M8 11V8a4 4 0 118 0v3" stroke="#94a3b8" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
							</svg>
						</span>
						<input id="password" name="password" type="password" placeholder="Contraseña" required aria-describedby="{{ $errors->has('password') ? 'password-error' : 'pwd-strength-text' }}" style="padding-right:100px;" autocomplete="current-password" />
						<button type="button" id="togglePwd" aria-label="Mostrar contraseña" style="position:absolute;right:8px;top:50%;transform:translateY(-50%);background:transparent;border:0;color:#374151;cursor:pointer;font-weight:600;padding:8px;border-radius:8px">Mostrar</button>

						<!-- medidor de fuerza -->
						<div style="position:absolute;left:12px;right:12px;bottom:-28px;">
							<div class="strength" id="pwdStrength" aria-hidden="true"><i></i></div>
							<div id="pwd-strength-text" class="strength-label" aria-live="polite"></div>
						</div>
					</div>

					@if ($errors->has('password'))
						<p id="password-error" class="error">{{ $errors->first('password') }}</p>
					@endif
				</div>
                <br>
				<!-- Remember / Forgot -->
				<div class="mt-4 row" style="margin-top:14px;">
					<label class="checkbox">
						<input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
						Recordarme
					</label>

					@if (Route::has('password.request'))
						<a class="link" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
					@endif
				</div>

				<!-- Submit -->
				<div style="margin-top:18px;">
					<button class="btn" type="submit" id="submitBtn">Iniciar sesión</button>
				</div>

				<div class="footer" style="margin-top:14px;">
					@if (Route::has('register'))
						<a style="color:var(--muted);text-decoration:underline" href="{{ Route::has('register') ? route('register') : url('/register') }}">¿No tienes cuenta? Regístrate</a>
					@endif
				</div>
			</form>
			<!-- FIN: formulario mejorado -->
		</div>
	</div>

	<!-- small script: toggle password -->
	<script>
		(function(){
			// elementos
			const email = document.getElementById('email');
			const emailWrap = document.getElementById('emailWrap');
			const emailClientErr = document.getElementById('email-client-error');
			const pwd = document.getElementById('password');
			const pwdStrength = document.getElementById('pwdStrength');
			const pwdStrengthBar = pwdStrength ? pwdStrength.querySelector('i') : null;
			const pwdStrengthText = document.getElementById('pwd-strength-text');
			const toggle = document.getElementById('togglePwd');
			const form = document.getElementById('loginForm');
			const submitBtn = document.getElementById('submitBtn');

			// validación sencilla de email
			function validEmail(v){
				return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
			}
			function validateEmail(){
				const v = email.value.trim();
				if (!v) {
					emailWrap.classList.remove('invalid');
					emailClientErr.style.display = 'none';
					emailClientErr.textContent = '';
					return;
				}
				if (!validEmail(v)){
					emailWrap.classList.add('invalid');
					emailClientErr.style.display = 'block';
					emailClientErr.textContent = 'Introduce un email válido.';
				} else {
					emailWrap.classList.remove('invalid');
					emailClientErr.style.display = 'none';
					emailClientErr.textContent = '';
				}
			}
			if (email) email.addEventListener('input', validateEmail);
			validateEmail();

			// fuerza de la contraseña (simple)
			function scorePassword(s){
				let score = 0;
				if (!s) return score;
				if (s.length >= 8) score++;
				if (s.length >= 12) score++;
				if (/[a-z]/.test(s) && /[A-Z]/.test(s)) score++;
				if (/\d/.test(s)) score++;
				if (/[^A-Za-z0-9]/.test(s)) score++;
				return Math.min(score,5);
			}
			function updatePwdStrength(){
				if (!pwdStrengthBar || !pwdStrengthText) return;
				const s = pwd.value || '';
				const score = scorePassword(s);
				const pct = (score/5)*100;
				pwdStrengthBar.style.width = pct + '%';
				// color scale
				if (score <=1) pwdStrengthBar.style.background = 'linear-gradient(90deg,#f97316,#ef4444)';
				else if (score ===2) pwdStrengthBar.style.background = 'linear-gradient(90deg,#f59e0b,#f97316)';
				else if (score ===3) pwdStrengthBar.style.background = 'linear-gradient(90deg,#facc15,#f59e0b)';
				else if (score ===4) pwdStrengthBar.style.background = 'linear-gradient(90deg,#84cc16,#a3e635)';
				else pwdStrengthBar.style.background = 'linear-gradient(90deg,#16a34a,#059669)';

				const labels = ['Muy débil','Débil','Aceptable','Fuerte','Muy fuerte'];
				pwdStrengthText.textContent = s ? labels[Math.max(0,score-1)] : '';
			}
			if (pwd) pwd.addEventListener('input', updatePwdStrength);
			updatePwdStrength();

			// toggle password
			if (toggle && pwd){
				toggle.addEventListener('click', function(){
					if (pwd.type === 'password') { pwd.type = 'text'; toggle.textContent = 'Ocultar'; toggle.setAttribute('aria-label','Ocultar contraseña'); }
					else { pwd.type = 'password'; toggle.textContent = 'Mostrar'; toggle.setAttribute('aria-label','Mostrar contraseña'); }
				});
			}

			// submit: deshabilitar botón y mostrar spinner simple
			if (form && submitBtn){
				form.addEventListener('submit', function(e){
					// validación cliente final
					validateEmail();
					if (emailWrap.classList.contains('invalid')){
						email.focus();
						e.preventDefault();
						return;
					}
					// evitar múltiples envíos
					submitBtn.disabled = true;
					submitBtn.style.opacity = '0.75';
					// agregar spinner
					const spinner = document.createElement('span');
					spinner.className = 'spinner';
					spinner.setAttribute('aria-hidden','true');
					submitBtn.appendChild(spinner);
				});
			}
		})();
	</script>
</body>
</html>
