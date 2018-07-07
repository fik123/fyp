<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('underground/MDB/css/compiled.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
</head>
{{-- <body background="{{ asset('84631.jpg')}}"> --}}
<body style="background: url({{asset('84631.jpg')}}) no-repeat;">    
    <div class="flex-center position-ref" style="height: -webkit-fill-available">
    	<div class="container">
    		<div class="row">
    			<div class="col-md-12">
    				<a href="{{ route('order.show',['order'=>$yourorderno,'tableno'=>$tableno]) }}"	class="btn peach-gradient btn-rounded" style=" width: -webkit-fill-available">Your order</a>
    				<a href="{{ route('order.create',['tableno'=>$tableno]) }}"	class="btn purple-gradient btn-rounded"  style=" width: -webkit-fill-available">New order</a>
                    <a href="{{ route('order.mass',['tableno'=>$tableno]) }}" class="btn purple-gradient btn-rounded"  style=" width: -webkit-fill-available">Mass order</a>
    			</div>	
    		</div>
    	</div>
    </div>
	<!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script> 
    <script src="{{ asset('underground/MDB/js/compiled.min.js') }}"></script>
</body>
</html>