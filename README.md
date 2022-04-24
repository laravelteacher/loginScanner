# loginScanner



install QrCode

composer require simplesoftwareio/simple-qrcode "~1"


<?php
    return [
    'providers' => [
        ....
        ....
        ....                
        SimpleSoftwareIO\QrCode\QrCodeServiceProvider::class,
    ],
    
    'aliases' => [
        ....
        ....
        ....                
        'QrCode' => SimpleSoftwareIO\QrCode\Facades\QrCode::class,
    ]


php artisan make:controller QrLoginController


<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class QrLoginController extends Controller
{   // this Function show that Page we want to loging by Scanner of QrCode
    public function index(Request $request) {
    
		return view('auth.QrLogin');
	}
	
	// this Function Allow to User log or no log that do by Scanner of QrCode
	public function checkUser(Request $request) {
		 $result =0;
			if ($request->data) {
				$user = User::where('name',$request->data)->first();
				if ($user) {
					Auth::login($user);
				    $result =1;
				 }else{
				 	$result =0;
				 }
            }
			return $result;
	}
}



make a Template to name QrLogin.blade.php in resources\views\auth folder for Scan & Log User 


@extends('layouts.app')
@section('content')
<div class="container">
<script src="https://reeteshghimire.com.np/wp-content/uploads/2021/05/html5-qrcode.min_.js"></script>
<!-- Header --> 
<div class="container-fluid header_se">
 <div class="col-md-8">
  <div class="row">
  <div class="col">
    <div id="reader"></div>
  </div>
  <div class="col" style="padding:30px;">
    <h4>SCAN RESULT</h4>
    <div id="result">Result Here</div>
  </div>
</div>
<script type="text/javascript">
  function onScanSuccess(data) {
    $.ajax({
      type: "POST",
      cache: false,
      url : "{{action('App\Http\Controllers\QrLoginController@checkUser')}}",
      data: {"_token": "{{ csrf_token() }}",data:data},
      success: function(data) {
       if (data==1) {
        document.getElementById('result').innerHTML = '<span class="result">'+'Logged'+'</span>';
          $(location).attr('href', '{{url('/home')}}');
            }
       else{
        return confirm('There is no user with this qr code'); 
       }
      }
    })
  }
  var html5QrcodeScanner = new Html5QrcodeScanner(
    "reader", { fps: 10, qrbox: 250 });
  html5QrcodeScanner.render(onScanSuccess);
</script>
</div>
</div>
</div>
<hr/>
<div class="container">
	 &copy; {{ date('Y') }}. Created by Alireza Moosavi
	 <br/>
</div>

<script type="text/javascript">
  $.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   }
  });
</script>
<style>
  .result{
    background-color: green;
    color:#fff;
    padding:20px;
  }
  .row{
    display:flex;
  }
  #reader {
    background: black;
    width:500px;
  }
  button {
  background-color: #4CAF50; /* Green */
  border: none;
  color: white;
  padding: 10px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
  border-radius: 6px;
}
a#reader__dashboard_section_swaplink {
  background-color: blue; /* Green */
  border: none;
  color: white;
  padding: 10px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
  border-radius: 6px;
}
span a{
  display:none
}

#reader__camera_selection{
  background: blueviolet;
  color: aliceblue;
}
#reader__dashboard_section_csr span{
  color:red
}
</style>
@yield('scripts')
@endsection

 php artisan migrate
 composer update

add Routes

Route::get('qrLogin', ['uses' => 'App\Http\Controllers\QrLoginController@index']);
Route::post('qrLogin', ['uses' => 'App\Http\Controllers\QrLoginController@checkUser']);
  
  
  add below code in home Template
  
  
  @extends('layouts.app')
@section('content')
<div class="container">
@guest
@if (Route::has('login'))
<h3>{{ __('You are not logged in Yet!') }}</h3>
@endif
@else
 <div class="row justify-content-center">
   <div class="col-md-8">
    <div class="card">
      <div class="card-header">{{ __('Dashboard') }}</div>
       <div class="card-body">
        <h3>
         {{ __('You are logged in!') }} </h3>
        <h4>{{ Auth::user()->name }}
         <div class="mb-3">
          <a href="data:image/png;base64,{!! base64_encode(QrCode::format('png')->size(400)->generate(Auth::user()->name))!!}" download="{{Auth::user()->name}}">
           <img src="data:image/png;base64,{!! base64_encode(QrCode::format('png')->size(400)->generate(Auth::user()->name))!!} ">
          </a>
         </div>
        </h4>
       </div>
      </div>
     </div>
   </div>
@endguest 
</div>
@endsection


add below code in app page

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
   
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
	<link href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
   
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                            
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }} </a>
                                </li>
                                <li><a class="nav-link" href="{{ url('qrLogin') }}">Qr Login</a></li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <span style='color:red'>Logged</span>
                                 {{ Auth::user()->name }} 
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>

