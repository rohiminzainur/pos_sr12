<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Expense;
use App\Models\Member;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $category = Category::count();
        $product = Product::count();
        $supplier = Supplier::count();
        $member = Member::count();

        $date_start = date('Y-m-01');
        $date_end = date('Y-m-d');

        $date_data = array();
        $data_income = array();

        while (strtotime($date_start) <= strtotime($date_end)) {
            $date_data[] = (int) substr($date_start, 8, 2);

            $total_sale = Sale::where('created_at', 'LIKE', "%$date_start%")->sum('paid');
            $total_purchase = Purchase::where('created_at', 'LIKE', "%$date_start%")->sum('paid');
            $total_expense = Expense::where('created_at', 'LIKE', "%$date_start%")->sum('nominal');

            $income = $total_sale - $total_purchase - $total_expense;
            $data_income[] += $income;

            $date_start = date('Y-m-d', strtotime("+1 day", strtotime($date_start)));
        }

        if (auth()->user()->level == 1) {
            return view('admin.dashboard', compact('category', 'product', 'supplier', 'member', 'date_start', 'date_end', 'date_data', 'data_income'));
        } else {
            return view('cashier.dashboard');
        }
    }
}