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
    public function index()
    {
        $services = Service::where('available', true)->paginate(6);
        return view('services.index', compact('services'));
    }

    public function show(Service $service)
    {
        return view('services.show', compact('service'));
    }
}
