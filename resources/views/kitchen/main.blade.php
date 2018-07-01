@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
    	<div class="col-md-9">
			<ul class="nav md-pills nav-justified pills-secondary">
			    <li class="nav-item">
			        <a class="nav-link active" data-toggle="tab" href="#panel14" role="tab">Main</a>
			    </li>
			    <li class="nav-item">
			        <a class="nav-link" data-toggle="tab" href="#panel11" role="tab">Mass</a>
			    </li>
			</ul>

			<!-- Tab panels -->
			<div class="tab-content">

			    <!--Panel 4-->
			    <div class="tab-pane fade in show active" id="panel14" role="tabpanel">
			        <br>
			        <div class="droptocook text-center" style="border:dashed grey;height: 100px">
			        	
			        	<span class="align-middle">Drop Here</span>
			        </div>
			        <br>

					<div class="cooked">
						<div class="row">
							<div class="card kikiwidth">
								<div class="card-body">
									<div class="row">
										<div class="col-md-2">
    										tablename
				    					</div>
				    					<div class="col-md-6">
				    						itemname
										</div>
										<div class="col-md-4">
											<div class="progress" style="height: 20px">
											    <div class="progress-bar " role="progressbar" style="width: 70%; height: 20px" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100">25%</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
			    </div>
			    <!--/.Panel 4-->
				<!--Panel 1-->
			    <div class="tab-pane fade" id="panel11" role="tabpanel">
			        <br>

			        <button type="button" class="btn btn-outline-info waves-effect" data-toggle="modal" data-target="#createmcook">+ New</button>
					<div class="mcooked">
						<div class="row">
							<div class="card kikiwidth">
								<div class="card-body">
									<div class="row">
										<div class="col-md-6">
    										menuname
				    					</div>
				    					<div class="col-md-2 kikiverticalleft">
				    						qty
										</div>
										<div class="col-md-2 kikiverticalleft">
				    						wt
										</div>
										<div class="col-md-2 kikiverticalleft">
				    						ct
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
			    </div>
			    <!--/.Panel 1-->
			</div>
    	</div>
    	<div class="col-md-3">
    		<div class="row">
    			<div class="card kikiwidth red">
				    <div class="card-body text-center">
				        <h3 class="white-text">Incoming Orders !!</h3>
				    </div>
				</div>
    		</div>
    		<div id="katsinikikipushorder" style="height: -webkit-fill-available;overflow-y: scroll"></div>
    		
    	</div>
    </div>
</div>
@endsection

@push('modalstacks')
	<div class="modal fade" id="createmcook" tabindex="-1" role="dialog" aria-labelledby="createmcook" aria-hidden="true">
	    <div class="modal-dialog" role="document">
	        <div class="modal-content">
	            <div class="modal-header text-center">
	                <h4 class="modal-title w-100 font-weight-bold">New Mass Cook</h4>
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                    <span aria-hidden="true">&times;</span>
	                </button>
	            </div>
	            <div class="modal-body mx-3">
	                <div class="md-form mb-5">
	                    <!--Blue select-->
						<select class="mdb-select colorful-select dropdown-primary" id="mcookmenu">
						    @foreach($menus as $menu)
						    	<option value="{{$menu->id}}">{{$menu->name}}</option>
						    @endforeach
						</select>
						<label>Menu</label>
						<!--/Blue select-->
	                </div>

	                <div class="md-form mb-5">
	                    <input type="number" id="mcookqty" name="mcookqty" class="form-control validate" min="2" value="2">
	                    <label for="mcookqty">Quantity</label>
	                </div>

	                <div class="md-form mb-5">
	                    <input type="text" id="mcookwt" name="mcookwt" class="form-control validate">
	                    <label data-error="wrong" data-success="right" for="mcookwt">Waiting Time (ms)</label>
	                </div>
	                <div class="md-form mb-5">
	                    <input type="text" id="mcookct" name="mcookct" class="form-control validate">
	                    <label data-error="wrong" data-success="right" for="mcookct">Cooking Time (ms)</label>
	                </div>
	            </div>
	            <div class="modal-footer d-flex justify-content-center">
	                <button class="btn btn-unique" id="btncreatemasscook">Send <i class="fa fa-paper-plane-o ml-1"></i></button>
	            </div>
	        </div>
	    </div>
	</div>
@endpush

@push('kikiscripts')
<script>
	
	window.orderdropfield 	= $('.droptocook')[0];
	window.cookedlist 	   	= $('.cooked')[0];
	window.newmasscook 		= {};
	window.gorderlist 		= '';
	window.ordersmcooks 	= {};
	// bile bende yg kite drag lalu sini
	function serveclick(e) {
		console.log("im here",e);
		var orderid = e.target.getAttribute("data-serve");
		$.ajax({
            type: 'PUT',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: '/order/'+orderid,
            data: JSON.stringify({"status": "rserve"}),
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
	orderdropfield.ondragover = function() {
        if ($(this).hasClass( "dragged" )) {
        }else{
            this.className += ' dragged';    
        }
        return false;
    }

    // bile bende yg kite drag kluar dri sini
    orderdropfield.ondragleave = function() {
        // this.className = 'upload-drop-zone';
        if ($(this).hasClass( "dragged" )) {
            this.classList.remove("dragged");
        }else{
            // this.className += ' dragged';    
        }
        return false;
    }

    // bile tgh drag
    function drag(dragevent) {
    	dragevent.dataTransfer.setData("order", dragevent.target.getAttribute("orderid")); // transfering data
    }
    // biledropped
    orderdropfield.ondrop = function(e) {
        e.preventDefault();
        var orderid = e.dataTransfer.getData("order");
        // start cook
        console.log($('meta[name="csrf-token"]').attr('content'));
        $.ajax({
            type: 'PUT',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: '/order/'+orderid,
            data: JSON.stringify({"status": "cooking"}),
            contentType: "application/json",
            success: function (data) { 
                if (data.status == 'success') {
                    toastr.success(data.msg);    
                    // cookedlist.appendChild($('.domid-'+orderid)[0]);
                    // var pgbar = '<div class="progress" style="height: 20px"><div class="progress-bar" role="progressbar" style="width: 70%; height: 20px" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100">25%</div></div>';
                    // $('.ordercount-'+data.order.id).html(pgbar);
                }else if(data.status == 'error'){
                    toastr.error(data.msg);
                }
            }
        }); 
    }



	window.setInterval(function(){
		// checking for new order
	  	console.log('new orders check');
	  	// ajax for sidebar
	  	$.ajax({
            type: 'GET',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: '{{route('order.index')}}',
            contentType: "application/json",
            success: function(data) {
            	// currentorders = data.orders.map(function (dor) { return dor.id });
            	// console.log(currentorders);
            	gorderlist = data.orders;
            	$("#katsinikikipushorder").empty();
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
    					nextitem += '				</div>';
    					nextitem += '			</div>';
    					nextitem += '		</div>';
    					nextitem += '	</div>';
    					nextitem += '</div>';
				var duration = moment.duration(moment().diff(moment(data.orders[i].created_at)));
            		$("#katsinikikipushorder").append(nextitem);
            		if (moment(data.orders[i].created_at).add(duration._milliseconds) >= moment(data.orders[i].created_at).add(data.ordersmenu[data.orders[i].id].time_taken)) {
	            			$('.ordercount-'+data.orders[i].id).addClass('red-text');
	            		}else{
	            			$('.ordercount-'+data.orders[i].id).addClass('green-text');
	            		}
            		$('.ordercount-'+data.orders[i].id).text(duration.hours() + "h" + duration.minutes() + "m" + duration.seconds() + "s");
            	}
            } 
        }); 

        // ajax forcooking
        $.ajax({
            type: 'GET',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: '{{route('order.cooking')}}',
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
            		if (duration._milliseconds >= data.ordersmenu[data.orders[i].id].time_taken) {
            			$('.ordercount-'+data.orders[i].id).addClass('red-text');
            			$('.ordercount-'+data.orders[i].id).html('<span onclick="serveclick(event)" class="badge badge-default" data-serve="'+data.orders[i].id+'">Serve</span>');
            		}else{
            			$('.ordercount-'+data.orders[i].id).addClass('green-text');
            			console.log('completed',duration._milliseconds/data.ordersmenu[data.orders[i].id].time_taken);
            			var wdval = parseFloat(duration._milliseconds/data.ordersmenu[data.orders[i].id].time_taken*100).toFixed(3);
            			console.log(wdval);
            			$('.ordercount-'+data.orders[i].id+' .progress .progress-bar').css('width', wdval+'%');
            			$('.ordercount-'+data.orders[i].id+' .progress .progress-bar').text(wdval+"%");
            		}
            	}
            } 
        }); 

        // ajax mass cooking
        $.ajax({
            type: 'GET',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: '{{route('mcook.index')}}',
            contentType: "application/json",
            success: function(data) {
            	$(".mcooked").empty();
            	for (var i = data.allmcooks.length - 1; i >= 0; i--) {
            		var mcooksgotorder = _.filter(gorderlist, { 'menu_id': data.allmcooks[i].menu_id});
            		if (mcooksgotorder.length) {
            			uordersmcookcallback(_.map(mcooksgotorder, 'id'),data.allmcooks[i].id);
            		}
            		if (data.allmcooks[i].orders) {
            			data.allmcooks[i].allorders = data.allmcooks[i].orders.split(',');
            			console.log(data.allmcooks[i].allorders);
            		}else{
            			data.allmcooks[i].allorders = [];
            		}
            		var mcookrow = '<div class="row">';
						mcookrow +='	<div class="card kikiwidth">';
						mcookrow +='		<div class="card-body">';
						mcookrow +='			<div class="row">';
						mcookrow +='				<div class="col-md-6">';
						mcookrow += data.mcooksmenu[data.allmcooks[i].id].name;
						mcookrow +='    			</div>';
						mcookrow +='    			<div class="col-md-2 kikiverticalleft">';
						mcookrow += data.allmcooks[i].allorders.length +'/'+data.allmcooks[i].qty;
						mcookrow +='				</div>';
						mcookrow +='				<div class="col-md-2 kikiverticalleft mcookswtcountdown-'+data.allmcooks[i].id+'">';
						mcookrow +=data.allmcooks[i].waiting_time;
						mcookrow +='				</div>';
						mcookrow +='				<div class="col-md-2 kikiverticalleft">';
						mcookrow +=data.allmcooks[i].cooking_time/1000 +'s';
						mcookrow +='				</div>';
						mcookrow +='			</div>';
						mcookrow +='		</div>';
						mcookrow +='	</div>';
						mcookrow +='</div>';
					var duration = moment.duration(moment().diff(moment(data.allmcooks[i].created_at)));
					$(".mcooked").append(mcookrow);
					if ((data.allmcooks[i].waiting_time - duration._milliseconds) <=0) {
						if (data.allmcooks[i].allorders.length == 0) {
							$(".mcookswtcountdown-"+data.allmcooks[i].id).text('no orders');
						}else{
							switch(data.allmcooks[i].status) {
							    case 'initialized':
							        $(".mcookswtcountdown-"+data.allmcooks[i].id).html('<button type="button" data-mcookid="'+data.allmcooks[i].id+'" class="btn btn-outline-danger waves-effect" onclick="umcook('+data.allmcooks[i].id+',\'started\')">Start</button>');
							        break;
							    case 'started':
							        $(".mcookswtcountdown-"+data.allmcooks[i].id).html('<div class="progress"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div></div>');
							        var duration = moment.duration(moment().diff(moment(data.allmcooks[i].updated_at)));
							        var mcpg = parseFloat(duration._milliseconds/data.allmcooks[i].cooking_time*100).toFixed(3);
							        console.log("mass cooking dur mili:",duration._milliseconds,"time taken",data.allmcooks[i].cooking_time,"%", mcpg);
							        if (mcpg >= 100) {
							        	// sent ajax to update mcook to ended
							        	umcook(data.allmcooks[i].id,'ended');
							        	$(".mcookswtcountdown-"+data.allmcooks[i].id).text('processing...');
							        }else{
							        	$(".mcookswtcountdown-"+data.allmcooks[i].id+" .progress .progress-bar").css('width', mcpg+'%');
							        }
							        break;
							    case 'ended':
							    	$(".mcookswtcountdown-"+data.allmcooks[i].id).html('<button type="button" data-mcookid="'+data.allmcooks[i].id+'" class="btn btn-outline-danger waves-effect" onclick="umcook('+data.allmcooks[i].id+',\'served\')">Serve</button>');	
							    	break;
							    default:
							        $(".mcookswtcountdown-"+data.allmcooks[i].id).html('confuse');	
							}
						}
					}else{
						$(".mcookswtcountdown-"+data.allmcooks[i].id).text(data.allmcooks[i].waiting_time - duration._milliseconds);	
					}
            	}
            			
            } 
        }); 
	}, 1000);

	$("#btncreatemasscook").on('click',function () {
        // jom submit
        newmasscook['menu_id'] 	= $('#mcookmenu').val();
        newmasscook['qty'] 		= $('#mcookqty').val();
        newmasscook['wt'] 		= $('#mcookwt').val();
        newmasscook['ct'] 		= $('#mcookct').val(); 
        console.log(newmasscook,JSON.stringify(newmasscook));
        $.ajax({
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: '{{route('mcook.store')}}',
            data: JSON.stringify(newmasscook),
            contentType: "application/json",
            success: function (data) { 
                if (data.status == 'success') {
                    toastr.success(data.msg);    
                }else if(data.status == 'error'){
                    toastr.error(data.msg);
                }
            }
        }); 

    });

    function uordersmcookcallback(orderids,mcookid) {
    	
    	ordersmcooks['orderids'] = orderids;
    	ordersmcooks['mcookid'] = mcookid;
    	ordersmcooks['toupdate'] = 'order';
    	console.log("callback get this: ",ordersmcooks);
    	$.ajax({
            type: 'PUT',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: '/mcook/'+mcookid,
            data: JSON.stringify(ordersmcooks),
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

    function umcook(mcookid,statupdate) {
    	console.log(mcookid);
    	ordersmcooks['mcookid'] = mcookid;
    	ordersmcooks['toupdate'] = 'status';
    	ordersmcooks['value'] = statupdate;
    	// update mcook to start
    	$.ajax({
            type: 'PUT',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: '/mcook/'+mcookid,
            data: JSON.stringify(ordersmcooks),
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
</script>
@endpush