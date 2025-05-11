<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Service::query();

        // البحث بكلمة مفتاحية
        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%")
                  ->orWhere('description', 'like', "%$keyword%");
            });
        }

        if ($request->filled('available')) {
            $query->where('available', $request->input('available'));
        }

        // ترتيب البيانات
        $allowedSortFields = ['id', 'name', 'price', 'available'];
        $sortBy = $request->input('sort_by', 'id'); // default: id
        $sortOrder = $request->input('sort_order', 'desc'); // asc or desc

        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'id';
        }

        if (!in_array(strtolower($sortOrder), ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }

        $query->orderBy($sortBy, $sortOrder);

        // pagination مع تمرير الفلاتر في الرابط
        $services = $query->paginate(10)->appends($request->query());

        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServiceRequest $request)
    {
        Service::create($request->validated());
        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        return view('services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ServiceRequest $request, Service $service)
    {
        $service->update($request->validated());
        if($request->input('available')) {
            $service->update(['available' => true]);
        }else {
            $service->update(['available' => false]);
        }

        if($service->wasChanged('available')) {
            $service->reservations()->update(['status_id' => $service->available ? 1 : 2]);
        }

        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
    }
}
