<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\ProductionOrder;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = ProductionOrder::count();

        $waitingMaterial = ProductionOrder::where('status', 'waiting_material')->count();

        $inProcess = ProductionOrder::where('status', 'process')->count();

        $completedToday = ProductionOrder::where('status', 'done')
            ->whereDate('updated_at', today())
            ->count();

        $recentlyCompleted = ProductionOrder::where('status', 'done')
            ->with('product')
            ->orderBy('end_date', 'desc')
            ->limit(5)
            ->get();

        return view('produksi.dashboard', compact(
            'totalOrders',
            'waitingMaterial',
            'inProcess',
            'completedToday',
            'recentlyCompleted'
        ));
    }

    public function progressReport()
    {
        $orders = ProductionOrder::with('product')->latest()->get();
        return view('produksi.reports.progress', compact('orders'));
    }

    public function efficiencyReport()
    {
        $orders = ProductionOrder::with('product')
            ->where('status', 'done')
            ->get();

        return view('produksi.reports.efficiency', compact('orders'));
    }

    public function dailyReport()
    {
        $orders = ProductionOrder::with('product')
            ->whereDate('created_at', today())
            ->get();

        return view('produksi.reports.daily', compact('orders'));
    }
}
