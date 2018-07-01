@extends('layouts.app')

@section('content')
	<ul class="nav md-pills nav-justified pills-secondary waitertab">
	    <li class="nav-item">
	        <a class="nav-link waiterready" data-toggle="tab" href="#panel11" role="tab">Ready</a>
	    </li>
	    <li class="nav-item">
	        <a class="nav-link waiterorder" data-toggle="tab" href="#panel12" role="tab">Order <button class="btn btn-flat" onclick="pagerefresh()"><i class="fa fa-refresh" aria-hidden="true"></i></button></a>
	    </li>
	</ul>

	<!-- Tab panels -->
	<div class="tab-content">

	    <!--Panel 1-->
	    <div class="tab-pane fade in show active" id="panel11" role="tabpanel">
	        <br>

	        <div class="cooked">
	        	
	        </div>

	    </div>
	    <!--/.Panel 1-->

	    <!--Panel 2-->
	    <div class="tab-pane fade" id="panel12" role="tabpanel">
	        <br>

	        <!--Accordion wrapper-->
            <div class="accordion" id="accordionEx" role="tablist" aria-multiselectable="true">
                @foreach($orders as $orderno => $customerorders)
                    
                    <!-- Accordion card -->
                    <div class="card">

                        <!-- Card header -->
                        <div class="card-header" role="tab" id="headingorder{{$orderno}}">
                            <a data-toggle="collapse" href="#collapse{{$orderno}}" aria-expanded="true" aria-controls="collapse{{$orderno}}">
                                <h5 class="mb-0">
                                    Order no: {{$orderno}} | Table no: {{$customerorders->first()->table->label}} |<button type="button" class="btn btn-outline-danger btn-rounded waves-effect" onclick="bulkcancel({{$orderno}})" >Force Cancel</button> <i class="fa fa-angle-down rotate-icon"></i> 
                                </h5>
                            </a>
                        </div>

                        <!-- Card body -->
                        <div id="collapse{{$orderno}}" class="collapse show" role="tabpanel" aria-labelledby="headingorder{{$orderno}}" data-parent="#accordionEx" >
                            <div class="card-body">
                                
                                
                                <ul class="list-group">
                                    @foreach($customerorders as $customerorder)    
                                    <li class="list-group-item ">
                                        {{$customerorder->menu->name}}
                                        <button type="button" class="btn btn-default pull-right" disabled>{{$customerorder->status}}</button>
                                        @if($customerorder->status == 'ordered')
                                        <button type="button" class="btn btn-outline-danger btn-rounded waves-effect pull-right" onclick="nakcancel({{$customerorder->id}})" >Cancel</button>
                                        @endif
                                    </li>
                                    @endforeach
                                </ul>
                                
                            </div>
                        </div>
                    </div>
                    <!-- Accordion card -->
                    
                @endforeach
            </div>
            <!--/.Accordion wrapper-->

	    </div>
	    <!--/.Panel 2-->

	</div>
@endsection

@push('kikiscripts')
<script>
    // window.thispage = getCookie("page");
    // console.log(thispage);
    window.cookiesinthepage = document.cookie.split(';');
    window.cookarray = {}
    for (var i = cookiesinthepage.length - 1; i >= 0; i--) {
        var c = cookiesinthepage[i].split('=');
        cookarray[c[0].replace(/^\s+|\s+$/g, '')] = c[1];
    }
    console.log(cookarray['page']);
    switch(cookarray['page']) {
        case 'waiterorder':
            $('.waitertab a.waiterorder').tab('show');
            break;
        case 'waiterready':
            $('.waitertab a.waiterready').tab('show');
            break;
        default:
            $('.waitertab a.waiterready').tab('show');
    }
    $('.waiterorder').on('shown.bs.tab', function (e) {
      // e.target // newly activated tab
      document.cookie = "page=waiterorder";
    });
    $('.waiterready').on('shown.bs.tab', function (e) {
      // e.target // newly activated tab
      document.cookie = "page=waiterready";
    });

	window.setInterval(()=>{
		// ajax forcooking
        $.ajax({
            type: 'GET',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: '{{route('order.waiter')}}',
            contentType: "application/json",
            success: function(data) {
            	// currentorders = data.orders.map(function (dor) { return dor.id });
            	// console.log(currentorders);
            	$(".cooked").empty();
            	for (var i = data.orders.length - 1; i >= 0; i--) {
            		
            		// created_at:"2018-06-15 15:01:26"id:15menu_id:2orderno:"4"paid:"0"qty:"1"status:"ordered"table_id:1takenby:"customer"updated_at:"2018-06-15 15:01:26"
            		var nextitem = 	'<div orderid="'+data.orders[i].id+'" ondragstart="drag(event)" draggable="true" class="row domid-'+data.orders[i].id+'">';
    					nextitem += '	<div class="card kikiwidth">';
    					nextitem += '		<div class="card-body">';
    					nextitem += '			<div class="row">';
    					nextitem += '				<div class="col-md-2">';
    					nextitem += 					data.orderstable[data.orders[i].id].label;
    					nextitem += '				</div>';
    					nextitem += '				<div class="col-md-6">';
    					nextitem += 					data.ordersmenu[data.orders[i].id].name;
    					nextitem += '				</div>';
    					nextitem += '				<div class="col-md-4 ordercount-'+data.orders[i].id+'">';
    					nextitem += '<div class="progress" style="height: 20px"><div class="progress-bar" role="progressbar" style="width: 0%; height: 20px" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div></div>';
    					nextitem += '				</div>';
    					nextitem += '			</div>';
    					nextitem += '		</div>';
    					nextitem += '	</div>';
    					nextitem += '</div>';
				var duration = moment.duration(moment().diff(moment(data.orders[i].updated_at)));
            		$(".cooked").append(nextitem);
            		console.log("dur mili:",duration._milliseconds,"timetaken:",data.ordersmenu[data.orders[i].id].time_taken);
            		var wt = parseFloat(duration._milliseconds/1000).toFixed(0);
            		var wtcol = (duration._milliseconds > 120000)? 'danger':'default';
            		$('.ordercount-'+data.orders[i].id).html('<span onclick="dahserve('+data.orders[i].id+')" class="badge badge-'+wtcol+'" data-serve="'+data.orders[i].id+'"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> delayed : '+wt+' (s)</span>');

            	}
            } 
        }); 
	},1000);

	function dahserve(orderid) {
		console.log("dah serve : ",orderid);
    	// update order status to served
    	$.ajax({
            type: 'PUT',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: '/order/'+orderid,
            data: JSON.stringify({"status": "served"}),
            contentType: "application/json",
            success: function (data) { 
                if (data.status == 'success') {
                    toastr.success(data.msg);    
                }else if(data.status == 'error'){
                    toastr.error(data.msg);
                }
            }
        }); 
	}
    function nakcancel(orderid) {
        console.log("dah cancel : ",orderid);
        // update order status to served
        $.ajax({
            type: 'PUT',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: '/order/'+orderid,
            data: JSON.stringify({"status": "cancelled"}),
            contentType: "application/json",
            success: function (data) { 
                if (data.status == 'success') {
                    toastr.success(data.msg);   
                    location.reload();
                }else if(data.status == 'error'){
                    toastr.error(data.msg);
                }
            }
        }); 
    }
    function bulkcancel(orderid) {
        console.log("dah cancel : ",orderid);
        // update order status to served
        $.ajax({
            type: 'PUT',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: '/bulkorder/'+orderid,
            data: JSON.stringify({"status": "cancelled"}),
            contentType: "application/json",
            success: function (data) { 
                if (data.status == 'success') {
                    toastr.success(data.msg);   
                    location.reload();
                }else if(data.status == 'error'){
                    toastr.error(data.msg);
                }
            }
        }); 
    }
    function pagerefresh() {
        location.reload();
    }
</script>
@endpush