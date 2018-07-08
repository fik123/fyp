<?php

namespace App\Http\Controllers;

use App\Mcook;
use Illuminate\Http\Request;
use App\Order;

class McookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allmcooks = Mcook::where('status','!=','served')->get();
        $mcooksmenu = [];
        foreach ($allmcooks as $key => $mcook) {
            $mcooksmenu[$mcook->id] = $mcook->menu;
        }
        // dd($mcooksmenu);
        return response()->json([
            'allmcooks' => $allmcooks,
            'mcooksmenu' => $mcooksmenu,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $param = $request->all();
        $newmcook = new Mcook;
        $newmcook->menu_id = $param['menu_id'];
        $newmcook->qty = $param['qty'];
        $newmcook->cooking_time = $param['ct'];
        $newmcook->waiting_time = $param['wt'];
        $newmcook->status = 'initialized';
        if ($newmcook->save()) {
            $response = [
                'status' => 'success',
                'msg'   => 'New Massive Cook: initialized',
                'menu' => $newmcook->menu,
            ];    
        }else{
            $response = [
                'status' => 'error',
                'msg'   => 'Fail to initialize massive cook',
                'menu' => $newmcook->menu,
            ];
        }
        
        return response()->json($response); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Mcook  $mcook
     * @return \Illuminate\Http\Response
     */
    public function show(Mcook $mcook)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Mcook  $mcook
     * @return \Illuminate\Http\Response
     */
    public function edit(Mcook $mcook)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Mcook  $mcook
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mcook $mcook)
    {
        $param = $request->all();
        if ($mcook->orders) {
            $currentmcookorders = explode(",",$mcook->orders);
        }else{
            $currentmcookorders = [];
        }
        switch ($param['toupdate']) {
            case 'order':
                if ($mcook->status != 'ended') {
                    foreach ($param['orderids'] as $key => $value) {
                        if (!in_array($value, $currentmcookorders)) {
                            array_push($currentmcookorders,$value);
                        }
                    }
                    // modify order status
                    foreach ($currentmcookorders as $key => $value) {
                        $order = Order::find($value);
                        $order->status = 'mcooking';
                        $order->save();
                    }
                    // modify mcook status
                    $mcook->orders = implode(",", $currentmcookorders); 
                }

                break;
            
            default:
                $mcook->status = $param['value'];
                if ($mcook->status == 'served') {
                    foreach ($currentmcookorders as $key => $order) {
                        $order = Order::find($order);
                        $order->status = 'rserve';
                        $order->save();
                    }
                }else if ($mcook->status == 'started') {
                    foreach ($currentmcookorders as $key => $order) {
                        $order = Order::find($order);
                        $order->status = 'mstartcooking';
                        $order->save();
                    }
                }
                
                break;
        }
        

        $mcook->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Mcook  $mcook
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mcook $mcook)
    {
        //
    }
}
