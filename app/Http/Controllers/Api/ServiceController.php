<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Traits\Response;

class ServiceController extends Controller
{
    use Response;

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

        return $this->sendRes(true, 'Services retrieved successfully', $query->paginate(config('app.per_page')));
    }
}
