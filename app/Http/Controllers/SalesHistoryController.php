<?php

namespace App\Http\Controllers;

use App\Models\Sale;

class SalesHistoryController extends Controller
{
    public function index()
    {
        $sales = Sale::history();

        return view('sales_history', compact('sales'));
    }
}
