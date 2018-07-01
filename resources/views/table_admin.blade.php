@extends('layouts.app')

@section('content')
<main>
	<div class="container-fluid">
		<div class="row">
			<div id="genqr" style="display: none">
				
			</div>
		</div>
	    <div class="row">
			<!-- Secondary button -->
			<button type="button" class="btn btn-secondary" style="font-size: 2em;" data-toggle="modal" data-target="#addtablemodal">
				<i class="fas fa-plus"></i>
				<i class="fas fa-utensils"></i>
			</button>
			<button type="button" class="btn btn-secondary" onclick="generateqr('all')" style="font-size: 2em;">
				GENERATE QR
			</button>
	    </div>
	    <div class="row">
	    	@foreach($tables as $table)
	    	<div class="col-md-2">
	    		<!-- Card -->
				<div class="card card-cascade wider kikiclicktableni" data-tablelabel="{{$table->label}}" data-tableid="{{$table->id}}" data-toggle="modal" data-target="#edittablemodal">

				  <!-- Card image -->
				  <div class="view gradient-card-header peach-gradient">
				    <!-- Title -->
				    <h2 class="card-header-title mb-3">{{$table->label}}</h2>
				    
				  </div>
				</div>
				<!-- Card -->
	    	</div>
	    	<div class="col-md-1">
	    		<!-- Card -->
				<div class="card card-cascade wider" data-tablelabel="{{$table->label}}" data-tableid="{{$table->id}}" onclick="generateqr({{$table->id}})">

				  <!-- Card image -->
				  <div class="view gradient-card-header peach-gradient">
				    <!-- Title -->
				    <h2 class="card-header-title mb-3" >QR</h2>
				    
				  </div>
				</div>
				<!-- Card -->
	    	</div>

	    	@endforeach
	    </div>
	</div>
</main>
@endsection

@push('modalstacks')

	<!-- Modal: add table -->
	<div class="modal fade" id="addtablemodal" tabindex="-1" role="dialog" aria-labelledby="addtablemodal" aria-hidden="true">
	    <div class="modal-dialog" role="document">
	        <div class="modal-content">
	           
				<!--Section: Live preview-->
				<section class="form-dark">
					{!! Form::open(['route' => 'table.store']) !!}
				    <!--Form without header-->
				    <div class="card card-image" style="background-image: url('https://mdbootstrap.com/img/Photos/Others/pricing-table7.jpg');">
				        <div class="text-white rgba-stylish-strong py-5 px-5 z-depth-4">

				            <!--Header-->
				            <div class="text-center">
				                <h3 class="white-text mb-5 mt-4 font-weight-bold">
				                	<strong><i class="fas fa-plus"></i></strong> 
				                	<a class="pink-text font-weight-bold">
				                		<strong> <i class="fas fa-utensils"></i></strong>
				                	</a>
				                </h3>
				            </div>

				            <!--Body-->
				            <div class="md-form">
				                <input type="text" id="tablelabel" name="label" class="form-control white-text">
				                <label for="tablelabel">Label</label>
				            </div>

				            <!--Grid row-->
				            <div class="row d-flex align-items-center mb-4">

				                <!--Grid column-->
				                <div class="text-center mb-3 col-md-12">
				                    <button type="submit" class="btn btn-secondary btn-block btn-rounded z-depth-1">Add Table</button>
				                </div>
				                <!--Grid column-->
				            </div>
				            <!--Grid row-->
				        </div>
				    </div>
				    <!--/Form without header-->
					{!! Form::close() !!}
				</section>
				<!--Section: Live preview-->
				            
	        </div>
	    </div>
	</div>

	<!-- Modal: edit/delete table-->
	<div class="modal fade" id="edittablemodal" tabindex="-1" role="dialog" aria-labelledby="edittablemodal" aria-hidden="true">
		<div class="modal-dialog modal-notify modal-danger" role="document">
		    <!--Content-->
		    <div class="modal-content">
		        <!--Header-->
		        <div class="modal-header">
		            <p class="heading lead">Warning!!</p>

		            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		                <span aria-hidden="true" class="white-text">&times;</span>
		            </button>
		        </div>

		        <!--Body-->
		        <div class="modal-body">
		            <div class="text-center">
		                {{-- <i class="fa fa-check fa-4x mb-3 animated rotateIn"></i> --}}
		                <form id="formedittableni" method="post">
		                	@csrf
			                <div class="md-form">
				                <input type="text" id="tablename" name="label" class="form-control black-text">
				                <label for="tablename" id="tablenamelabel">Table name</label>
				            </div>
			            </form>
		            </div>
		        </div>

		        <!--Footer-->
		        <div class="modal-footer justify-content-center">
		            <a type="button" class="btn btn-outline-danger waves-effect" data-dismiss="modal" id="updatetablebutton">Update</a>
		            <a type="button" class="btn btn-danger" data-dismiss="modal" id="deletetablebutton">Remove <i class="fa fa-diamond ml-1"></i></a>
		        </div>
		    </div>
		    <!--/.Content-->
		</div>
	</div>
	<!-- Central Modal Medium Success-->
@endpush
@push('kikiscripts')
<script>
	$( ".kikiclicktableni" ).click(function() {
		$("#formedittableni").attr("action","/table/"+$(this).data("tableid"));
		$("#tablename").val($(this).data("tablelabel"));
		$("#tablenamelabel").attr("class","active");
	});
	$( "#updatetablebutton" ).click(function() {
		// kalau update
		if ($("#formedittableni>input[name=_method]")) {
			$("#formedittableni>input[name=_method]").remove();
		}
		$("#formedittableni").append('<input type="hidden" name="_method" value="put" />');
		$("#formedittableni").submit();
	});
	$( "#deletetablebutton" ).click(function() {
		if ($("#formedittableni>input[name=_method]")) {
			$("#formedittableni>input[name=_method]").remove();
		}
		$("#formedittableni").append('<input type="hidden" name="_method" value="delete" />');
		$("#formedittableni").submit();
	});
	function generateqr(table) {

		if (table == 'all') {
			window.location = "http://localhost:8000/admingenqr/"+table;
		}else{
			window.location = "http://localhost:8000/admingenqr/"+table;
		}

		
	}
</script>
@endpush