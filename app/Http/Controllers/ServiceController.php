<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route; // <--- añadido

class ServiceController extends Controller
{
    // GET /providers/{provider}/services
    public function index(Provider $provider)
    {
        $services = $provider->services()->paginate(5);
        return view('services.index', compact('provider', 'services'));
    }

    // GET /providers/{provider}/services/create
    public function create(Provider $provider)
    {
        return view('services.create', compact('provider'));
    }

    // POST /providers/{provider}/services
    public function store(Request $request, Provider $provider)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $data['provider_id'] = $provider->id;
        Service::create($data);

        return redirect()->route('providers.services.index', $provider)->with('success', 'Servicio creado');
    }

    // GET /services/{service}/edit
    public function edit(Service $service)
    {
        $service->load('provider');
        return view('services.edit', compact('service'));
    }

    // PUT /services/{service}
    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $service->update($data);

        return redirect()->route('providers.services.index', $service->provider)->with('success', 'Servicio actualizado');
    }

    // DELETE /services/{service}
    public function destroy(Service $service)
    {
        $service->delete();
        return back()->with('success', 'Servicio eliminado');
    }

    // GET /services/trash
    public function trash()
    {
        $services = Service::onlyTrashed()->with('provider')->paginate(5);
        return view('services.trash', compact('services'));
    }

    // PUT /services/{service}/restore
    public function restore($id)
    {
        $service = Service::withTrashed()->find($id);

        if (! $service) {
            abort(404);
        }

        $service->restore();

        return redirect()->back()->with('success', 'Servicio restaurado correctamente.');
    }

    // GET /services
    public function indexAll(Request $request)
    {
        // obtener todos los servicios paginados, junto con su proveedor si existe
        $services = Service::with('provider')->paginate(5);

        // pasar lista de proveedores para el modal de creación (id + name)
        $providers = Provider::orderBy('name')->get(['id','name']);

        // si por compatibilidad se espera $provider en la vista, lo dejamos nulo
        $provider = null;

        // comprobar si existe una ruta 'services.store' (compatibilidad con versiones previas)
        $storeRouteObj = Route::getRoutes()->getByName('services.store') ?? null;
        $serviceStoreAllowsPost = $storeRouteObj ? in_array('POST', $storeRouteObj->methods()) : false;

        return view('services.all', compact('services', 'providers', 'provider', 'serviceStoreAllowsPost'));
    }

    /**
     * Display the specified resource.
     *
     * If a dedicated view 'services.show' exists it will be returned.
     * Otherwise redirect to the edit page to keep behavior useful and avoid a BadMethodCallException.
     */
    public function show(Service $service)
    {
        // If you have a show view, return it:
        if (view()->exists('services.show')) {
            return view('services.show', compact('service'));
        }

        // Fallback: redirect to the edit page (existing route/view)
        return redirect()->route('services.edit', $service);
    }
}
