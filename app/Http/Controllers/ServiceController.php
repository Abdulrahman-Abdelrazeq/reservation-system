<?php

namespace App\Http\Controllers;

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
        $services = Service::where('available', true);

        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $services->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%")
                  ->orWhere('description', 'like', "%$keyword%");
            });
        }

        $allowedSortFields = ['name', 'price'];
        $sortBy = $request->input('sort_by', 'id'); // default: id
        $sortOrder = $request->input('sort_order', 'desc'); // asc or desc

        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'id';
        }

        if (!in_array(strtolower($sortOrder), ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        $services->orderBy($sortBy, $sortOrder);

        // pagination
        $services = $services->paginate(config('app.per_page'))->appends($request->query());

        return view('services.index', compact('services'));
    }

    public function show(Service $service)
    {
        return view('services.show', compact('service'));
    }
}
