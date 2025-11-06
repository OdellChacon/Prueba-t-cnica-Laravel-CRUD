<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;
use App\Http\Requests\ProviderRequest;

class ProviderController extends Controller
{
	public function index(Request $request)
	{
		$providers = Provider::query()
			->when($request->q, fn($q) => $q->where(function($s) use ($request) {
				$s->where('name', 'like', '%'.$request->q.'%')
				  ->orWhere('email', 'like', '%'.$request->q.'%')
				  ->orWhere('phone', 'like', '%'.$request->q.'%');
			}))
			->orderBy('name')
			->paginate(5);
		return view('providers.index', compact('providers'));
	}

	public function create()
	{
		return view('providers.create');
	}

	public function store(ProviderRequest $request)
	{
		Provider::create($request->validated());
		return redirect()->route('providers.index')->with('success','Proveedor creado.');
	}

	public function edit(Provider $provider)
	{
		return view('providers.edit', compact('provider'));
	}

	public function update(ProviderRequest $request, Provider $provider)
	{
		$provider->update($request->validated());
		return redirect()->route('providers.index')->with('success','Proveedor actualizado.');
	}

	public function destroy(Provider $provider)
	{
		$provider->delete(); // soft delete si model usa SoftDeletes
		return redirect()->route('providers.index')->with('success','Proveedor eliminado.');
	}

	// Mostrar proveedores eliminados (papelera)
	public function trash()
	{
		$providers = Provider::onlyTrashed()->orderBy('deleted_at','desc')->paginate(5);
		return view('providers.trash', compact('providers'));
	}

	// Restaurar proveedor por id (PUT)
	public function restore($id)
	{
		$provider = Provider::withTrashed()->findOrFail($id);
		if ($provider->trashed()) {
			$provider->restore();
			return redirect()->route('providers.trash')->with('success', 'Proveedor restaurado.');
		}
		return redirect()->route('providers.trash')->with('success', 'El proveedor no estaba eliminado.');
	}
}
