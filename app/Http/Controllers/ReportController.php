<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

    // public function index(){
    //   // $today = now()->toDateString();
    //   //  $orders = Sale::whereDate('created_at',$today)->with('user')
    //   $orders = Sale::with('user')
    //     ->withCount('saleItems') // items count
    //     ->latest()
    //     ->get();

    // return response()->json([
    //     'status' => 'success',
    //     'data' => $orders
    // ]);
    // }

    public function index(Request $request)
    {
        $search = $request->search;
        $orders = Sale::with('user')
            ->withCount('saleItems') // items count
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });
            })
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $orders
        ]);
    }


    public function dailyReport()
    {
        //now mean current date and time, toDateString() method will return only date in Y-m-d format   
        $today = now()->toDateString();
        // Get all sales for today created_at = today
        $sales = Sale::whereDate('created_at', $today)->get();
        $totalRevenue = $sales->sum('total_amount');
        $totalSales = $sales->count();
        if ($totalSales == 0) {
            return response()->json([
                'message' => 'No sales found for today'
            ], 404);
        }
        return response()->json([
            'date' => $today,
            'total_revenue' => $totalRevenue,
            'total_sales' => $totalSales,
            'sales' => $sales
        ]);
    }


    public function monthLyReport()
    {
        $month = now()->month; //Get current month as a number (1-12)
        $year = now()->year; //Get current year
        $sales = Sale::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)->get();
        $totalRevenue = $sales->sum('total_amount');
        $totalSales = $sales->count();
        return response()->json([
            'month' => $month,
            'year' => $year,
            'total_revenue' => $totalRevenue,
            'total_sales' => $totalSales,
            'sales' => $sales
        ]);
    }

    public function topProducts()
    {
        $products = SaleItem::select(
            'product_id',
            DB::raw('SUM(quantity) as total_quantity'),
            DB::raw('SUM(subtotal) as total_revenue')
        )
            ->groupBy('product_id')
            ->with('product')
            ->orderByDesc('total_quantity')
            ->limit(4)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $products
        ]);
    }
}
