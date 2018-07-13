<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Order;
use App\Mcook;
class CustomerController extends Controller
{
    /**
    *
    *   Changes for main
    *   Description :  function where user go here after scan 
    *   Last edited by : Firdausneonexxa
    *
    */
        
    public function main (Request $request){
        return view('customer.main');
    }

    /**
    *
    *   Changes for option
    *   Description :   show user option
    *   Last edited by : Firdausneonexxa
    *
    */
        
    public function option (Request $request){
        $parameters = $request->all();
        $tid = $parameters['tid'];
        $tableid = DB::table('tables')->where('label', 'like', '%' .$parameters['tid']. '%')->first()->id;
        $latestorderofthattable = Order::where('table_id',$tableid)->latest()->first();
        if ($latestorderofthattable) {
            $yourorderno = $latestorderofthattable->orderno;
        }else{
            // kalau table x penah order lagi
            $yourorderno = 0;   
        }
        
        $tableno = $tableid;
        return view('customer.option',compact('yourorderno','tableno','tid'));
    }
    public function orders($orderno)
    {
        $allactivemcooking = Mcook::all();
        $orders = Order::where('orderno','=',$orderno)->get();
        $ordersmenu = [];
        $ordercookingtime = [];
        foreach ($orders as $key => $order) {
            $ordersmenu[$order->id] = $order->menu;

            if ($order->status == 'cooking') {
                $ordercookingtime[$order->id] = $order->menu->time_taken;
            }elseif($order->status == 'mstartcooking'){

                foreach ($allactivemcooking as $key => $activemcooking) {
                    $mcookorderids = explode(',', $activemcooking->orders);
                    if (in_array($order->id, $mcookorderids)) {
                        $ordercookingtime[$order->id] = $activemcooking->cooking_time;        
                    }
                }
                
            }else{
                $ordercookingtime[$order->id] = $order->menu->time_taken;
            }
            
        }
            $response = [
                'status' => 'success',
                'msg'   => 'all my orders',
                'orders' => $orders,
                'ordersmenu' => $ordersmenu,
                'ordercookingtime' => $ordercookingtime,
            ];
        
        
        return response()->json($response); 
    }
    	
    	
}
