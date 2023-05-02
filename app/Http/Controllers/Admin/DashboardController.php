<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LiveUser;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderProduct;
use Auth;

class DashboardController extends Controller
{
  // Dashboard - Analytics
  public function dashboardAnalytics()
  {
    

    $pageConfigs = ['pageHeader' => false];

    return view('/content/dashboard/dashboard-analytics', ['pageConfigs' => $pageConfigs]);
  }

  // Dashboard - Ecommerce
  public function dashboardEcommerce()
  {
    $todayOrders = Order::whereDate('created_at', Carbon::today()->toDateString())->count();
    $userName = Auth::user()->name;
    $customers = User::where('role_id',3)->count();
    $products = Product::count();
    $objOrderProduct = OrderProduct::get();
    $objTodayOrderProduct = OrderProduct::whereDate('created_at', Carbon::today()->toDateString())->get();

    //sales
    $totalsales = $objOrderProduct->sum(function($t){ 
      return $t->quantity * $t->price; 
    });

    //profit
    $totalCosting = $objOrderProduct->sum(function($t){ 
      return $t->cost_per_item * $t->quantity; 
    });
    $profit = $this->displayValue($totalsales-$totalCosting);
    $totalsales = $this->displayValue($totalsales);

    //todaysales
    $todayTotalSales = $objTodayOrderProduct->sum(function($t){ 
                       return $t->quantity * $t->price; 
                      });
    //todayprofit
    $todayTotalCosting = $objTodayOrderProduct->sum(function($t){ 
                       return $t->cost_per_item * $t->quantity; 
                      });
    $todayTotalProfit = $this->displayValue($todayTotalSales-$todayTotalCosting);
    $todayTotalSales = $this->displayValue($todayTotalSales);


    $pageConfigs = ['pageHeader' => false];
    $trackMinute = Config('site.TRACKMINUTE');
    $liveCartUser = LiveUser::where('cart','!=','0')->whereBetween('updated_at', [now()->subMinutes($trackMinute), now()])->DISTINCT('cart')->count();
    $liveCheckoutUser = LiveUser::where('checkout','!=','0')->whereBetween('updated_at', [now()->subMinutes($trackMinute), now()])->DISTINCT('checkout')->count();
    $livePurchasedUser = LiveUser::where('purchase','!=','0')->whereBetween('updated_at', [now()->subMinutes($trackMinute), now()])->DISTINCT('purchase')->count();
    $totalLiveUser = LiveUser::where('live','!=','0')->whereBetween('updated_at', [now()->subMinutes($trackMinute), now()])->DISTINCT('live')->count();
    $data = [
        'liveCartUser' => $liveCartUser,
        'liveCheckoutUser' => $liveCheckoutUser,
        'livePurchasedUser' => $livePurchasedUser,
        'totalLiveUser' => $totalLiveUser,
        'todayOrders' => $todayOrders,
        'customers' => $customers,
        'products' => $products,
        'userName' => $userName,
        'totalsales' => $totalsales,
        'todayTotalSales' => $todayTotalSales,
        'profit' => $profit,
        'todayTotalProfit' => $todayTotalProfit,
    ];

    return view('/content/dashboard/dashboard-ecommerce', compact('pageConfigs','data'));
  }
}
