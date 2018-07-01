@extends('layouts.customerapp')
@section('content')
<!--Panel-->
<div class="card">
    <div class="card-header">
        <!--Tabs-->
        <ul class="nav nav-pills card-header-pills">
            <li class="nav-item">
                <a class="nav-link active" href="#">My Orders</a>
            </li>
        </ul>
        <!--/.Tabs-->
    </div>
    <div class="card-body">
    	@if($orders->isEmpty())
        <h5 class="card-title">No orders</h5>
        @else
        <h5 class="card-title">Order no : {{$orders->first()->orderno}}</h5>
        @endif
        <!--Table-->
		<table class="table">

		    <!--Table head-->
		    <thead>
		        <tr>
		            <th>#</th>
		            <th>Item</th>
		            <th>Qty</th>
		            <th>Price</th>
		            <th>Avg.Time</th>
		            <th>Total</th>
		        </tr>
		    </thead>
		  <!--Table head-->

		    <!--Table body-->
		    <tbody>
		    	@foreach($orders as $order)
		        <tr>
		            <th scope="row">{{$order->id}}</th>
		            <td>{{$order->menu->name}}</td>
		            <td>1</td>
		            <td>{{$order->menu->price}}</td>
		            <td>{{$order->menu->time_taken}}</td>
		            <td>{{$order->totalpriceofthisorder}}</td>
		        </tr>
		        @endforeach
		        <tr>
		        	<td colspan="4"></td>
		        	<td><strong>Total</strong></td>
		        	<td>{{array_sum(array_column($orders->toArray(), 'totalpriceofthisorder'))}}</td>
		        </tr>
		    </tbody>
		    <!--Table body-->

		</table>
		<!--Table-->
        <a href="{{route('order.create',['tableno'=>$tableno])}}" class="btn btn-primary">Go to Menu</a>
    </div>
</div>
<!--/.Panel-->
@endsection