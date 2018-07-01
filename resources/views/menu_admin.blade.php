@extends('layouts.app')

@section('content')
<main>
<div class="container-fluid">
    <div class="row">
		<!-- Secondary button -->
		<button type="button" class="btn btn-secondary" style="font-size: 2em;" data-toggle="modal" data-target="#addmenumodal">
			<i class="fas fa-plus"></i>
			<i class="fas fa-book"></i>
		</button>
    </div>
    <div class="row">
    	@foreach($menus as $menu)
    	<div class="col-md-4">
    		<!-- Card Narrower -->
			<div class="card card-cascade narrower">

			  <!-- Card image -->
			  <div class="view overlay">
			    <img class="card-img-top" src="https://mdbootstrap.com/img/Photos/Lightbox/Thumbnail/img%20(147).jpg" alt="Card image cap">
			    <a>
			      <div class="mask rgba-white-slight"></div>
			    </a>
			  </div>

			  <!-- Card content -->
			  <div class="card-body">

			    <!-- Label -->
			    <h5 class="pink-text pb-2 pt-1"><i class="fas fa-utensils"></i> {{$menu->time_taken}} m</h5>
			    <!-- Title -->
			    <h4 class="card-title">{{$menu->name}}</h4>
			    <!-- Text -->
			    <p class="card-text">{{$menu->description}}</p>
			    <!-- Button -->
			    <a class="btn btn-unique">RM {{$menu->price}}</a>

			  </div>

			</div>
			<!-- Card Narrower -->
    	</div>
    	@endforeach
    </div>
</div>
</main>
@endsection

@push('modalstacks')

<div class="modal fade" id="addmenumodal" tabindex="-1" role="dialog" aria-labelledby="addmenumodal" aria-hidden="true">
    <!--Modal: Contact form-->
    <div class="modal-dialog cascading-modal" role="document">

        <!--Content-->
        <div class="modal-content">

            <!--Header-->
            <div class="modal-header primary-color white-text">
                <h4 class="title">
                    <i class="fa fa-pencil"></i>New Menu</h4>
                <button type="button" class="close waves-effect waves-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <!--Body-->
            <div class="modal-body">
				{!! Form::open(['route' => 'menu.store']) !!}
                <!-- Material input name -->
                <div class="md-form form-sm">
                    <i class="fa fa-envelope prefix"></i>
                    <input type="text" id="menuname" name="name" class="form-control form-control-sm">
                    <label for="menuname">Menu</label>
                </div>

                <!-- Material textarea message -->
                <div class="md-form form-sm">
                    <i class="fa fa-pencil prefix"></i>
                    <textarea type="text" id="menudescription" name="description" class="md-textarea form-control"></textarea>
                    <label for="menudescription">Description</label>
                </div>

				<div class="row">

					<!-- Grid column -->
					<div class="col-md-6">
						<div class="md-form mb-0">
							<input type="number" id="price" name="price" class="form-control" min="0">
							<label for="price" class="">Price (RM)</label>
						</div>
					</div>
					<!-- Grid column -->

					<!-- Grid column -->
					<div class="col-md-6">
						<div class="md-form mb-0">
							<input type="number" id="time_taken" name="time_taken" class="form-control" min="0">
							<label for="time_taken" class="">Time taken (Minutes)</label>
						</div>
					</div>
					<!-- Grid column -->
		        </div>
		        <!-- Grid row -->
                <div class="text-center mt-4 mb-2">
                    <button type="submit" class="btn btn-primary">Add New Menu
                        <i class="fa fa-send ml-2"></i>
                    </button>
                </div>
				{!! Form::close() !!}
            </div>
        </div>
        <!--/.Content-->
    </div>
    <!--/Modal: Contact form-->
</div>

@endpush