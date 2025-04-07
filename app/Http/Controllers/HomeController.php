<?php

namespace App\Http\Controllers;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(Request $request): Response
    {
        $vehicles = Vehicle::with(['photos', 'make', 'model'])
            ->when($request->make_id, fn($query) => $query->where('make_id', $request->make_id)) 
            ->when($request->model_id, fn($query) => $query->where('model_id', $request->model_id)) 
            ->when($request->year, fn($query) => $query->whereYear('year', $request->year))
            ->when($request->min_price, fn($query) => $query->where('price', '>=', $request->min_price))
            ->when($request->max_price, fn($query) => $query->where('price', '<=', $request->max_price))
            ->latest()
            ->paginate(12);
    

        return Inertia::render('Welcome', [
            'vehicles' => $vehicles,
        ]);
    }
}
