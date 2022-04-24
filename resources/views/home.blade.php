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
             <!-- below code show the name of User like QRCode and user can download that image to login -->
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