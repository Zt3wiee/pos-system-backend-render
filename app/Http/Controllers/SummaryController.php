<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    public function summary()
    {
        $today = now()->toDateString();
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        $startOfYear = now()->startOfYear();
        $endOfYear = now()->endOfYear();
        $start = microtime(true);

        //today sales
        $todaySales = Sale::whereDate('created_at', $today)->sum('total_amount');
        //today orders count
        $todayOrders = Sale::whereDate('created_at', $today)->count();



        //Monthly sales
        $monthlySales = Sale::whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('total_amount');
        //Monthly orders count
        $monthlyOrders = Sale::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();


        //Yearly sales
        $YearlySales = Sale::whereBetween('created_at', [$startOfYear, $endOfYear])->sum('total_amount');
        //Yearly orders count
        $YearlyOrders = Sale::whereBetween('created_at', [$startOfYear, $endOfYear])->count();

        return response()->json([
            'query_time' => microtime(true) - $start,
            'todayRevenue' => $todaySales,
            'todayOrders' => $todayOrders,
            'monthRevenue' => $monthlySales,
            'monthOrders' => $monthlyOrders,
            'yearRevenue' => $YearlySales,
            'yearOrders' => $YearlyOrders,
        ]);
    }
//     public function summary()
// {
//     $start = microtime(true);
//     return response()->json([
//         'query_time' => microtime(true) - $start,
//         'message' => 'hello'
//     ]);
// }
}
