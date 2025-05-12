<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Service::where('available', true);

        // بحث بكيوورد
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // ترتيب
        if ($request->filled('sort_by')) {
            $direction = $request->get('order', 'asc');
            $query->orderBy($request->sort_by, $direction);
        }

        return response()->json($query->paginate(10));
    }
}
