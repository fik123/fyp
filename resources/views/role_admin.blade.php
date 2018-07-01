@extends('layouts.app')

@section('content')
<main>
<div class="container-fluid">
    {{$roles}}
    <br>
    {{$users}}
    <!--Table-->
	<table class="table">

	    <!--Table head-->
	    <thead class="mdb-color darken-3">
	        <tr class="text-white">
	            <th>#</th>
	            <th width="70%">User</th>
	            <th width="20%">Role</th>
	        </tr>
	    </thead>
	    <!--Table head-->

	    <!--Table body-->
	    <tbody>
	    	@foreach($users as $user)
	        <tr>
	            <th scope="row">1</th>
	            <td>{{$user->name}}</td>
	            <td><!--Blue select-->
					<select class="mdb-select colorful-select dropdown-secondary">
						@foreach($roles as $role)
					    	<option value="{{$role->id}}" @if($user->role->id == $role->id) selected @endif >{{$role->title}}</option>
					    @endforeach
					</select>
					<label>Blue select</label>
					<!--/Blue select-->
				</td>
	        </tr>
	        @endforeach
	    </tbody>
	    <!--Table body-->

	</table>
	<!--Table-->
</div>
</main>
@endsection
