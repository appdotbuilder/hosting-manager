<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::with(['customer', 'serviceType'])
            ->latest()
            ->paginate(10);
        
        return Inertia::render('services/index', [
            'services' => $services
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        $service->load(['customer', 'serviceType']);

        return Inertia::render('services/show', [
            'service' => $service
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'status' => 'required|in:pending,active,suspended,cancelled'
        ]);

        $service->update([
            'status' => $request->status
        ]);

        return redirect()->route('services.show', $service)
            ->with('success', 'Service status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Service deleted successfully.');
    }
}