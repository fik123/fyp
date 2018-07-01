<?php

namespace App\Http\Controllers;

use App\Order;
use App\Menu;
use App\Table;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allorders = Order::where('status','ordered')->get();
        $ordersmenu = [];
        $orderstable = [];
        foreach ($allorders as $key => $order) {
            $ordersmenu[$order->id] = $order->menu;
            $orderstable[$order->id] = $order->table;
        }
        // dd($allorders);
        return response()->json([
            'orders' => $allorders,
            'ordersmenu' => $ordersmenu,
            'orderstable' => $orderstable
        ]);
    }
    public function indexcooking()
    {
        $allorders = Order::where('status','cooking')->get();
        $ordersmenu = [];
        $orderstable = [];
        foreach ($allorders as $key => $order) {
            $ordersmenu[$order->id] = $order->menu;
            $orderstable[$order->id] = $order->table;
        }
        // dd($allorders);
        return response()->json([
            'orders' => $allorders,
            'ordersmenu' => $ordersmenu,
            'orderstable' => $orderstable
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($tableno)
    {
        $menus = Menu::all()->groupBy('type');
        return view('customer.menu',compact('menus','tableno'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function masscreate($tableno)
    {
        return view('customer.mass',compact('tableno'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$tableno)
    {
        $statusincomplete = 0;
        $items = $request->all();
        $lastorderofthattable = Table::find($tableno)->orders->last();
        $lastorder = Order::latest('orderno')->first();

        if ($lastorderofthattable) {
            $recentorders = Order::where('orderno',$lastorderofthattable->orderno)->get();
            foreach ($recentorders as $key => $recentorder) {
                if(!$recentorder->paid){
                    $statusincomplete += 1;    
                }
            }
            $totalmenu = count($items);
            $menusaved = 0;
            $lastorderno = '';
            foreach ($items as $key => $item) {
                $neworder = new Order;
                // $neworder->qty      = 1;//$item['qty'];
                $neworder->status   = 'ordered';
                $neworder->paid     = 0;
                $neworder->table_id = $tableno;
                $neworder->menu_id  = $item['menu'];
                if ($statusincomplete) {
                    $neworder->orderno = $lastorderofthattable->orderno;
                }else{
                    $neworder->orderno = (is_null($lastorder))? 1: $lastorder->orderno+1;
                }
                
                if ($neworder->save()) {
                    $menusaved++;  
                    $lastorderno = $neworder->orderno;
                }
            }
            if ($menusaved == $totalmenu) {
                $response = [
                    'status' => 'success',
                    'msg'   => 'successfully ordered',
                    'input' => $request->all(),
                    'orderno' => $lastorderno,
                ];    
            }else{
                $response = [
                    'status' => 'error',
                    'msg'   => 'failed to submit order',
                    'input' => $request->all(),
                ];
            }
            
            return response()->json($response); 
        }else{
            $totalmenu = count($items);
            $menusaved = 0;
            $lastorderno = '';
            foreach ($items as $key => $item) {
                $neworder = new Order;
                // $neworder->qty      = 1;//$item['qty'];
                $neworder->status   = 'ordered';
                $neworder->paid     = 0;
                $neworder->table_id = $tableno;
                $neworder->menu_id  = $item['menu'];
                $neworder->orderno = (is_null($lastorder))? 1:$lastorder->orderno+1;
                
                if ($neworder->save()) {
                    $menusaved++;  
                    $lastorderno = $neworder->orderno;
                }
            }
            if ($menusaved == $totalmenu) {
                $response = [
                    'status' => 'success',
                    'msg'   => 'successfully ordered',
                    'input' => $request->all(),
                    'orderno' => $lastorderno,
                ];    
            }else{
                $response = [
                    'status' => 'error',
                    'msg'   => 'failed to submit order',
                    'input' => $request->all(),
                ];
            }
            
            return response()->json($response); 
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($order,$tableno)
    {
        $orders = Order::where('orderno',$order)->latest()->get();
        foreach ($orders as $key => $order) {
            $order->totalpriceofthisorder = 1*$order->menu->price;
        }
        return view('customer.myorder',compact('orders','tableno'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $param = $request->all();
        switch ($param['status']) {
            case 'cooking':
                $order->status = 'cooking';
                $msg    = 'Cooking begin : '.$order->menu->name. '...';
                $ermsg  = '[Warning] Stove missing : Fail to cook '.$order->menu->name. '!!!';
                break;
            
            case 'rserve':
                $order->status = 'rserve';
                $msg = 'Ready to serve : '.$order->menu->name. ' !!!';
                $ermsg  = '[Warning] Stove missing : Fail to serve '.$order->menu->name. '!!!';
                break;

            case 'served':
                $order->status = 'served';
                $msg = 'Served ['.$order->menu->name.'] to table : '.$order->table->label. ' !!!';
                $ermsg  = '[Warning] Stove missing : Fail to serve '.$order->menu->name. '!!!';
                break;

            case 'cancelled':
                $order->status = 'cancelled';
                $msg = 'Cancelled ['.$order->menu->name.'] to table : '.$order->table->label. ' !!!';
                $ermsg  = '[Warning] Stove missing : Fail to cancel '.$order->menu->name. '!!!';
                break;

            case 'paid':
                $order->status = ($order->status == 'served') ? 'completed' : $order->status;
                $order->paid = 1;
                $msg = 'Completed Payment of ['.$order->menu->name.'] on table : '.$order->table->label. ' !!!';
                $ermsg  = '[Warning] Stove missing : Fail to cancel '.$order->menu->name. '!!!';
                break;

            default:
                $msg = 'Opps please give a status';
                $ermsg  = '[Danger] Unknown status : '.$order->menu->name. '!!!';
                break;
        }
        
        if ($order->save()) {
            $response = [
                'status' => 'success',
                'msg'   => $msg,
                'order' => $order,
            ];    
        }else{
            $response = [
                'status' => 'error',
                'msg'   => $ermsg,
                'order' => $order,
            ];
        }
        
        return response()->json($response); 
    }
    public function bulkupdate(Request $request, $order)
    {
        $param = $request->all();
        
        $orders = Order::where('orderno',$order)->get();
        $ordersave = 0;
        switch ($param['status']) {
            case 'cancelled':
                foreach ($orders as $key => $value) {
                    if($value->status == 'cooking' || $value->status == 'mcooking' || $value->status == 'ordered'){
                        $value->status = 'cancelled';
                    }
                    if ($value->save()) {
                        $ordersave++;
                    }
                }
                $msg = 'Cancelled ['.$order.']';
                $ermsg  = '[Warning] Stove missing : Fail to cancel '.$order. '!!!';
                break;
            case 'paid':
                if(!empty($param['orderids']) && $param['status'] == 'paid'){

                    foreach ($param['orderids'] as $orderid) {

                        $order = Order::find($orderid);
                        $order->status = ($order->status == 'served') ? 'completed' : $order->status;
                        $order->paid = 1;
                        if ($order->save()) {
                            $ordersave++;
                        }
                    }
                    $msg = 'Paid '.implode(",",$param['orderids']);
                }elseif(empty($param['orderids']) && $param['status'] == 'paid'){
                    foreach ($orders as $key => $value) {
                        if(!$value->paid && $value->status != 'cancelled'){
                            $value->status = ($value->status == 'served') ? 'completed' : $value->status;
                            $value->paid = 1;
                        }
                        if ($value->save()) {
                            $ordersave++;
                        }
                    }    
                    $msg = 'Paid All for '.$orders->first()->orderno;

                }
                break;
            default:
                $msg = 'Opps please give a status';
                $ermsg  = '[Danger] Unknown status : '.$order. '!!!';
                break;
        }
        if(!empty($param['orderids']) && $param['status'] == 'paid'){
            if (count($param['orderids']) == $ordersave) {
                $response = [
                    'status' => 'success',
                    'msg'   => $msg,
                    'orders' => $param['orderids'],
                ];    
            }   
        }else{
            if (count($orders) == $ordersave) {
                $response = [
                    'status' => 'success',
                    'msg'   => $msg,
                    'orders' => $orders,
                ];    
            }else{
                $response = [
                    'status' => 'error',
                    'msg'   => $ermsg,
                    'order' => $order,
                ];
            }    
        }

        
        
        return response()->json($response); 
    }

    
        

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    /**
    *
    *   Changes for waiter
    *   Description :   
    *   Last edited by : Firdausneonexxa
    *
    */
        
    public function waiter (){
        $allorders = Order::where('status','rserve')->get();
        $ordersmenu = [];
        $orderstable = [];
        foreach ($allorders as $key => $order) {
            $ordersmenu[$order->id] = $order->menu;
            $orderstable[$order->id] = $order->table;
        }
        // dd($allorders);
        return response()->json([
            'orders' => $allorders,
            'ordersmenu' => $ordersmenu,
            'orderstable' => $orderstable
        ]);
    }

    /**
    *
    *   Changes for Index
    *   Description :   
    *   Last edited by : Firdausneonexxa
    *
    */
        
    public function search (Request $request){
        $parameters = $request->all();
        if ($parameters['orderno']) {
            $orders = Order::where('orderno',$parameters['orderno'])->get();
            foreach ($orders as $key => $value) {
                $value->menuprice = $value->menu->price;
            }
            return view('cashier.main',compact('orders'));
        }
    }
        
        
}