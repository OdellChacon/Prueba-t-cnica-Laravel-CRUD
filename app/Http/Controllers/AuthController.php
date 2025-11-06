<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
	/**
	 * Procesa el login usando Auth::attempt
	 */
	public function login(Request $request)
	{
		$request->validate([
			'email'    => ['required', 'email'],
			'password' => ['required'],
		]);

		$credentials = $request->only('email', 'password');

		if (Auth::attempt($credentials, $request->filled('remember'))) {
			$request->session()->regenerate();
			return redirect()->intended(route('dashboard'));
		}

		return back()
			->withErrors(['email' => 'Credenciales inválidas.'])
			->withInput();
	}

	/**
	 * Cierra la sesión de forma segura
	 */
	public function logout(Request $request)
	{
		Auth::logout();
		$request->session()->invalidate();
		$request->session()->regenerateToken();

		return redirect()->route('home');
	}
}
