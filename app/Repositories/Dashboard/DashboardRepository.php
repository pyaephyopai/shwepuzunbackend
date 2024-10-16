<?php

namespace App\Repositories\Dashboard;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class DashboardRepository implements DashboardRepositoryInterface
{

    private $months = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December'
    ];


    public function dashboard($request)
    {

        $newUsers = User::whereMonth('created_at', Carbon::now()->month)->count();
        $previousMonth = Carbon::now()->subMonth();
        $previousMonthNewUsers = User::whereMonth('created_at', $previousMonth->month)
            ->whereYear('created_at', $previousMonth->year)
            ->count();

        $userConditionStatus = $newUsers > $previousMonthNewUsers ? 1 : 0;
        $totalUsers = User::get()->count();

        $newOrders = Order::whereMonth('created_at', Carbon::now()->month)->count();
        $previousOrders = Order::whereMonth('created_at', $previousMonth->month)->whereYear('created_at', $previousMonth->year)->count();
        $totalOrders = Order::get()->count();
        $orderConditionStatus = $newOrders > $previousOrders ? 1 : 0;
        $totalProducts = Product::get()->count();

        $currentSales = Order::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_price');

        $totalSales = Order::get()->sum('total_price');

        $totalPreviousSales = Order::whereMonth('created_at', $previousMonth->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_price');

        $saleCondition = $currentSales > $totalPreviousSales ? 1 : 0;

        return [
            'new_users' => $newUsers,
            'total_users' => $totalUsers,
            'user_condition' => $userConditionStatus,
            'new_orders' => $newOrders,
            'total_orders' => $totalOrders,
            'order_condition' => $orderConditionStatus,
            'total_products' => $totalProducts,
            'total_sales' => $totalSales,
            'current_sales' => $currentSales,
            'sale_condition' => $saleCondition,
        ];
    }

    public function monthlySales($request)
    {

        $salesData = [];

        foreach ($this->months as $month) {
            $totalSales = Order::whereMonth('created_at', Carbon::parse($month)->month)->sum('total_price');

            $salesData[] = [
                'month' => $month,
                'sales' => $totalSales,
            ];
        }
        return $salesData;
    }

    public function monthlyUsers($request)
    {

        $totalUsers = [];

        foreach ($this->months as $month) {
            $users = User::whereMonth('created_at', Carbon::parse($month)->month)->count();

            $totalUsers[] = [
                'month' => $month,
                'newUsers' => $users
            ];
        }
        return $totalUsers;
    }
}
