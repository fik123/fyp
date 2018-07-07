
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
	<a href="{{route('order.create',['tableno'=>$tableno])}}" class="btn btn-primary">Go to Menu</a>
</div>



@endsection

@push('scripts')
<script>
window.itemsincart = [];
$(".joinmass").on('click',function() {
    // if (!_.find(itemsincart, ['menu', $(this).data('menu')])) {
        // var idx = _.findLastIndex(itemsincart);
        itemsincart.push({
       "menu":$(this).data('menu'),
       "name":$(this).data('name'),
       "price":$(this).data('price'),
       "avgtime":$(this).data('avgtime'),
       "qty": 1
       });
    // }
    console.log(itemsincart);
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
window.setInterval(function(){

	var mcooklist = '';
	// begin
	
	// endforeach
	$.ajax({
        type: 'GET',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: '/mcook/',
        contentType: "application/json",
        success: function(data) {
            $("#masslist").empty();
            for (var i = data.allmcooks.length - 1; i >= 0; i--) {

            	var duration = moment.duration(moment().diff(moment(data.allmcooks[i].created_at)));
				var remtime = (data.allmcooks[i].waiting_time - duration._milliseconds - 5000)/1000;
				var currentmcparticipant = data.allmcooks[i].orders.split(",");

				if (remtime > 0 && data.allmcooks[i].qty > currentmcparticipant.length) {
					mcooklist += '<div class="row">';
					mcooklist += '	<div class="card card-body">';
					mcooklist += '		<h4 class="card-title">'+data.mcooksmenu[data.allmcooks[i].id].name+'</h4>';
					mcooklist += '		<p class="card-text" id="waitingtime'+data.allmcooks[i].id+'"></p>';
					mcooklist += '		<div class="flex-row">';
					mcooklist += '			<a class="card-link joinmass" data-menu="'+data.mcooksmenu[data.allmcooks[i].id].id+'" data-name="'+data.mcooksmenu[data.allmcooks[i].id].name+'" data-price="'+data.mcooksmenu[data.allmcooks[i].id].price+'" data-avgtime="">joinbtn</a>';
					mcooklist += '		</div>';
					mcooklist += '	</div>';
					mcooklist += '</div>';
					
					$("#waitingtime"+data.allmcooks[i].id).text(remtime+"s");
					console.log(remtime);
				}
            }
			$("#masslist").append(mcooklist);
        } 
    }); 
}, 1000);
</script>
@endpush