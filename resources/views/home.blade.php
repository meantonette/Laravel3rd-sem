{{-- @extends('layouts.base')
@section('head')
@parent
 <link rel="stylesheet" href="another.css" />
 @stop

 @section('body')
 <h1>Hurray!</h1>
 <p>We have a template!</p>
 @stop --}}


 @extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
