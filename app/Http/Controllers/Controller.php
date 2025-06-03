<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
