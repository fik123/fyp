@extends('layouts.app')

@section('content')
<main>
	<div class="container-fluid">
        <div class="row">
            @foreach($tables as $table)
            <div class="col-md-3">
                <!-- Card -->
                <div class="card card-cascade wider" data-tablelabel="{{$table->label}}" data-tableid="{{$table->id}}" onclick="opentable({{$table->id}})">

                  <!-- Card image -->
                  <div class="view gradient-card-header peach-gradient">
                    <!-- Title -->
                    <h2 class="card-header-title mb-3">{{$table->label}}</h2>
                  </div>
                </div>
                <!-- Card -->
            </div>
            @endforeach
        </div>
    </div>
</main>
@endsection

@push('kikiscripts')
<script>
    function opentable(tableid) {
        window.location = '/kitchen/cashier/'+tableid;
    }
</script>
@endpush
