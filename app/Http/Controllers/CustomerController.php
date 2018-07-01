<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Order;
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
        $tableid = DB::table('tables')->where('label', 'like', '%' .$parameters['tid']. '%')->first()->id;
        $latestorderofthattable = Order::where('table_id',$tableid)->latest()->first();
        if ($latestorderofthattable) {
            $yourorderno = $latestorderofthattable->orderno;
        }else{
            // kalau table x penah order lagi
            $yourorderno = 0;   
        }
        
        $tableno = $tableid;
        return view('customer.option',compact('yourorderno','tableno'));
    }
    	
    	
}
