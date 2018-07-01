<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Table;
use App\Order;
class PaymentController extends Controller
{
    /**
    *
    *   Changes for Index
    *   Description :   
    *   Last edited by : Firdausneonexxa
    *
    */
        
    public function tablecashout (Table $table){
    	$orderno = 0;
    	foreach ($table->orders as $key => $value) {
    		$orderno = ($value->orderno > $orderno)? $value->orderno : $orderno;
    	}
    	$orders = Order::where('orderno',$orderno)->get();
    	foreach ($orders as $key => $value) {
    		$value->menuprice = $value->menu->price;
    	}
        return view('cashier.main',compact('orders'));
    }

    public function cashierorder (Order $order){
        
        return response()->json([
            'order' => $order,
            'ordermenu' => $order->menu
        ]);
    }
    
    

    /**
    *
    *   Changes for Index
    *   Description :   
    *   Last edited by : Firdausneonexxa
    *
    */
        
    public function cashierselected (Request $request, $order){
    	$param = $request->all();
    	$totalprice = 0;
    	if (empty($param['ids'])) {
    		return response()->json([
	            'error' => 'ids is empty'
	        ]);
    	}else{
    		$selectedorderids = explode(",", $param['ids']);
    		foreach ($selectedorderids as $value) {
    			$selectedorder = Order::find($value);
    			$totalprice += $selectedorder->menu->price; 
    		}
    	}
    	
        return response()->json([
            'orders' => $param['ids'],
            'totalprice' => $totalprice
        ]);
    }
    	
	/**
	*
	*   Changes for Index
	*   Description :   
	*   Last edited by : Firdausneonexxa
	*
	*/
	    
	public function cashierall ($order){
		$orders = Order::where('orderno',$order)->where('status','!=','cancelled')->where('status','!=','completed')->where('paid','!=',1)->get();
		foreach ($orders as $key => $value) {
			$value->menuprice = $value->menu->price;
		}
		
		$totalprice = $orders->sum('menuprice');
		$selectedids = $orders->where('status','!=','cancelled')->where('status','!=','completed')->where('paid','!=',1)->pluck('id');
	    return response()->json([
            'orders' => $selectedids,
            'totalprice' => $totalprice
        ]);
	}
		

    	
}
