
@extends('layouts.customerapp')
@section('content')
<div class="container">
	<br>
	<br>
	<br>
	<h4>Mass Order List</h4>
	<br>
	<div id="masslist">

	</div>	
    <br>
    <h4>All Orders</h4>
    <div id="alllist">

    </div>  
	<a href="{{route('order.create',['tableno'=>$tableno])}}" class="btn btn-primary">Go to Menu</a>
    <a href="{{route('customer.option',['tid'=>$tid])}}" class="btn btn-primary">Go to Option</a>
</div>



@endsection

@push('scripts')
<script>
window.itemsincart = [];
// watcher join masscook
$('#masslist').on('click', '.joinmass', function() {
    // console.log("watcher",$('#masslist .joinmass').data("mcooks"));
    let mcookid = $('#masslist .joinmass').data("mcooks");
    console.log("mcookid",mcookid);
    itemsincart.push({
        "menu":$('#masslist .joinmass').data("menu"),
        "name":$('#masslist .joinmass').data('name'),
        "price":$('#masslist .joinmass').data('price'),
        "avgtime":$('#masslist .joinmass').data('avgtime'),
        "qty": 1
    });
    console.log("itemcard",itemsincart);
    $.ajax({
        type: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: '{{route('order.store',['tableno'=>$tableno])}}',
        data: JSON.stringify(itemsincart),
        contentType: "application/json",
        success: function (data) { 
            if (data.status == 'success') {
                toastr.success(data.msg);    
                window.location =  '/order/'+data.orderno+'/'+{{$tableno}};
            }else if(data.status == 'error'){
                toastr.error(data.msg);
            }
        }
    }); 
});
// end watcher

window.setInterval(function(){

	
	// begin
	
	// endforeach
	$.ajax({
        type: 'GET',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: '/mcook/',
        contentType: "application/json",
        success: function(data) {
            $("#masslist").empty();
            var mcooklist = '';
            // console.log("mcook",data.allmcooks.length,);
            for (var i = data.allmcooks.length - 1; i >= 0; i--) {
                // console.log("dump");
            	var duration = moment.duration(moment().diff(moment(data.allmcooks[i].created_at)));
				var remtime = (data.allmcooks[i].waiting_time - duration._milliseconds - 5000)/1000;
				var currentmcparticipant = data.allmcooks[i].orders.split(",");

				
					mcooklist += '<div class="row">';
					mcooklist += '	<div class="card card-body">';
					mcooklist += '		<h4 class="card-title">'+data.mcooksmenu[data.allmcooks[i].id].name+' ~ <span>('+currentmcparticipant.length +'/' +data.allmcooks[i].qty  +')</span></h4>';
					mcooklist += '		<p class="card-text" id="waitingtime'+data.allmcooks[i].id+'"></p>';
					mcooklist += '		<div class="flex-row">';
                    if (data.allmcooks[i].qty > currentmcparticipant.length) {
					mcooklist += '			<button class="btn btn-default joinmass" data-mcooks="'+data.allmcooks[i].id+'" data-menu="'+data.mcooksmenu[data.allmcooks[i].id].id+'" data-name="'+data.mcooksmenu[data.allmcooks[i].id].name+'" data-price="'+data.mcooksmenu[data.allmcooks[i].id].price+'" data-avgtime="">Join</button>';
                    }else{
                    mcooklist += '<button class="btn btn-brown"> FULL </button>';
                    }
					mcooklist += '		</div>';
                    mcooklist += '      <div class="flex-row">';
                    mcooklist += '          <p> Status: '+translatestatus(data.allmcooks[i].status) +'</p>';
                    mcooklist += '      </div>';
					mcooklist += '	</div>';
					mcooklist += '</div>';
					
					$("#waitingtime"+data.allmcooks[i].id).text(remtime+"s");
					console.log(remtime);
				
            }
			$("#masslist").append(mcooklist);
        } 
    }); 
    $.ajax({
        type: 'GET',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: '{{route('order.cooking')}}',
        contentType: "application/json",
        success: function(data) {
            console.log("ordercooking",data);
            $("#alllist").empty();
            var mcooklist = '';
            for (var i = data.orders.length - 1; i >= 0; i--) {
                // console.log("haiya",data.orders[i].status);
                var duration = moment.duration(moment().diff(moment(data.orders[i].created_at)));
                // var waittime = (data.ordersmenu[data.orders[i].id].time_taken - duration._milliseconds)/1000;
                // var currentmcparticipant = data.orders[i].orders.split(",");

                // if (data.allmcooks[i].qty > currentmcparticipant.length) {
                    mcooklist += '<div class="row">';
                    mcooklist += '  <div class="card card-body">';
                    mcooklist += '      <h4 class="card-title">'+data.ordersmenu[data.orders[i].id].name+'</h4>';
                    mcooklist += '      <p class="card-text" id="waitingtime'+data.orders[i].id+'"></p>';
                    mcooklist += '      <div class="flex-row">';
                    // mcooklist += '          <a class="card-link" data-menu="'+data.ordersmenu[data.orders[i].id].id+'" data-name="'+data.ordersmenu[data.orders[i].id].name+'" data-price="'+data.ordersmenu[data.orders[i].id].price+'" data-avgtime="">joinbtn</a>';
                    mcooklist += '      </div>';
                    mcooklist += '      <div class="flex-row">';
                    mcooklist += '          <p> Status: '+translatestatus(data.orders[i].status )+'</p>';
                    mcooklist += '      </div>';
                    mcooklist += '  </div>';
                    mcooklist += '</div>';
                    
                    // $("#waitingtime"+data.orders[i].id).text(duration._milliseconds/1000 +"s");
                    // console.log(remtime);
                // }
            }
            $("#alllist").append(mcooklist);
        } 
    }); 
}, 1000);
function translatestatus(exp) {
    switch(exp) {
        case 'initialized':
            return "Chef is waiting for orders";
            break;
        case 'started':
            return "Chef has started cooking";
            break;
        case 'ended':
            return "Cooking has ended";
            break;
        case 'ordered':
            return "Waiting for chef to cook";
            break;
        case 'cooking':
            return "Chef is cooking";
            break;
        case 'rserve':
            return "Cooking has ended, waiter is serving";
            break;
        case 'served':
            return "Received";
            break;
        default:
            return "Chef main mobile legend";
    }
}

</script>
@endpush