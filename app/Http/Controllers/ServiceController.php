<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $services = Service::all();
        return view('services.index', compact('services'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        return view('services.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|in:kg,piece',
        ]);

        Service::create($validated);
        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|in:kg,piece',
        ]);

        $service->update($validated);
        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
    }
}
