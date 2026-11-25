<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductionOrder;
use App\Models\Material;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Redirect based on role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'gudang') {
            return redirect()->route('gudang.dashboard');
        } elseif ($user->role === 'produksi') {
            return redirect()->route('produksi.dashboard');
        }

        // Default dashboard view
        return view('dashboard');
    }
}
