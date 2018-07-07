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
    <div class="card-body" style="height: -webkit-fill-available">
    	@if($orders->isEmpty())
        <h5 class="card-title">No orders</h5>
        @else
        <h5 class="card-title">Order no : {{$orders->first()->orderno}}</h5>
        @endif
        <div class="myorders">
        	addasdsa
		</div>
		<p>Total : {{array_sum(array_column($orders->toArray(), 'totalpriceofthisorder'))}}</p>
		<a href="{{route('order.create',['tableno'=>$tableno])}}" class="btn btn-primary">Go to Menu</a>
    </div>
</div>
<!--/.Panel-->
@endsection

@push('scripts')
<script>
	window.setInterval(function(){
		// checking for new order
	  	console.log('orders check');
	  	// ajax for menu
	  	$.ajax({
            type: 'GET',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: '{{route('customer.order',['orderno'=>$orders->first()->orderno])}}',
            contentType: "application/json",
            success: function(data) {
            	console.log("data:",data);
            	
            	$(".myorders").empty();
            	for (var i = 0 ; i < data.orders.length; i++) {

 
            		var myordersbody = 	'<div class="card card-body">';
					    myordersbody += '	<h6 class="card-title">IDX'+data.orders[i].id+'ODR ~ '+data.ordersmenu[data.orders[i].id].name+'</h6>';
					    myordersbody += '	<p>Avg. Cooking time ~ <span style="color:red">'+data.ordersmenu[data.orders[i].id].time_taken/60000+' m </span></p>';
					    myordersbody += '	<p>Est. Cooking time ~ <span style="color:red">'+data.ordercookingtime[data.orders[i].id]/60000+' m </span></p>';
					    if (data.orders[i].status == 'cooking' || data.orders[i].status == 'mstartcooking') {
					    	myordersbody += '	<div class="progress mcookswtcountdown-'+data.orders[i].id+'" style="height: 20px">';
						    myordersbody += '		<div class="progress-bar" role="progressbar" style="width: 25%; height: 20px" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>';
						    myordersbody += '	</div>';
					    }
					    
					
					    myordersbody += '	<div class="flex-row">';
					    myordersbody += '		<p>Status: '+data.orders[i].status+'</p>';
					    myordersbody += '	</div>';
					    myordersbody += '</div><br>';
					    $(".myorders").append(myordersbody);
					    if (data.orders[i].status == 'cooking' || data.orders[i].status == 'mstartcooking') {
					    	var duration = moment.duration(moment().diff(moment(data.orders[i].updated_at)));
					    	var cooktimeorderpg = parseFloat(duration._milliseconds/data.ordercookingtime[data.orders[i].id]*100).toFixed(3);
					    	if (cooktimeorderpg >=100) {
					    		$(".mcookswtcountdown-"+data.orders[i].id).text('Waiting chef confirmation...');
					    	}else{
					    		$(".mcookswtcountdown-"+data.orders[i].id+" .progress-bar").css('width', cooktimeorderpg+'%');
					    	}
					    	
					    }
            	}
            	
            }
        });

    }, 1000);
		
	// var duration = moment.duration(moment().diff(moment(data.allmcooks[i].updated_at)));
	// var mcpg = parseFloat(duration._milliseconds/data.allmcooks[i].cooking_time*100).toFixed(3);
	// console.log("mass cooking dur mili:",duration._milliseconds,"time taken",data.allmcooks[i].cooking_time,"%", mcpg);
	// if (mcpg >= 100) {
	// 	// sent ajax to update mcook to ended
	// 	umcook(data.allmcooks[i].id,'ended');
	// 	$(".mcookswtcountdown-"+data.allmcooks[i].id).text('processing...');
	// }else{
	// 	$(".mcookswtcountdown-"+data.allmcooks[i].id+" .progress .progress-bar").css('width', mcpg+'%');
	// }
</script>
@endpush