<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Purchasing;
use App\Models\Sales;
use App\Models\SalesItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function index(Request $request)
    {
        $user = Auth::user();
        $isKasir = $user->role && $user->role->name === 'kasir';
        if ($isKasir) {
            // Jika role adalah kasir, tampilkan halaman kosong atau halaman khusus untuk kasir
            return view('blank', compact('isKasir'));
        }

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $purchasingQuery = Purchasing::whereNull('deleted_at');
        $salesQuery = Sales::whereNull('sales.deleted_at');

         if ($startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();

            $purchasingQuery->whereBetween('date', [$start, $end]);
            $salesQuery->whereBetween('date', [$start, $end]);
        }
        $totalPurchasing = $purchasingQuery->sum('smallPrice');

    $totalSales = (clone $salesQuery)->sum('totalPrice');

    $totalProfit = (clone $salesQuery)
        ->where('status', 'LUNAS')
        ->join('sales_items', function ($join) {
            $join->on('sales.id', '=', 'sales_items.sales_id')
                ->whereNull('sales_items.deleted_at');
        })
        ->sum(DB::raw('(sales_items.sellingPricePerUnit - sales_items.pricePerUnit) * sales_items.qty'));

    $totalDebt = (clone $salesQuery)
        ->where('remainingPayment', '>', 0)
        ->sum('remainingPayment');

    $monthlyProfit = Sales::select(
        DB::raw('MONTH(sales.date) as month'),
        DB::raw('SUM((sales_items.sellingPricePerUnit - sales_items.pricePerUnit) * sales_items.qty) as total_profit')
    )
        ->join('sales_items', 'sales.id', '=', 'sales_items.sales_id')
        ->where('sales.status', 'LUNAS')
        ->whereNull('sales.deleted_at')
        ->whereNull('sales_items.deleted_at')
        ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            $query->whereBetween('sales.date', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        })
        ->groupBy(DB::raw('MONTH(sales.date)'))
        ->orderBy(DB::raw('MONTH(sales.date)'))
        ->get();

    $salesData = Sales::join('sales_items', 'sales.id', '=', 'sales_items.sales_id')
        ->whereNull('sales.deleted_at')
        ->whereNull('sales_items.deleted_at')
        ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            $query->whereBetween('sales.date', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        })
        ->select(
            'sales_items.productName',
            DB::raw('SUM(sales_items.qty) as total_sold')
        )
        ->groupBy('sales_items.productName')
        ->orderBy('total_sold', 'DESC')
        ->get();

    return view('dashboard', compact(
        'totalPurchasing',
        'totalSales',
        'totalProfit',
        'totalDebt',
        'salesData',
        'monthlyProfit',
        'isKasir'
    ));
    }
}
