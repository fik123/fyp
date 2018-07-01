<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Menu;
use App\Order;
use App\Table;

class KitchenController extends Controller
{
    /**
    *
    *   Changes for main kitchen
    *   Description :   
    *   Last edited by : Firdausneonexxa
    *
    */
        
    public function main (){
        $menus = Menu::all();
        return view('kitchen.main',compact('menus'));
    }

    public function waiter (){
        $orders = Order::all()->groupBy('orderno');
        foreach ($orders as $orderno => $thiscustomerorder) {
            
            $incompleteorder = $thiscustomerorder->where('status', '!=', 'completed')->all();
            
            if (empty($incompleteorder)) {
                $orders->forget($orderno);   
            }
        }
        // dd($orders);
        
        return view('kitchen.waiter',compact('orders'));
    }

    /**
    *
    *   Changes for cashier
    *   Description :   
    *   Last edited by : Firdausneonexxa
    *
    */
        
    public function cashier (){
        $tables = Table::all();
        return view('kitchen.cashier',compact('tables'));
    }
        
    	
}
