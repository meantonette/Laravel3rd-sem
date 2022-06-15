@extends('layouts.base')
{{-- @extends('layouts.app')
@section('content') --}}
@section('body')
  <div class="container">
    <br />
    @if ( Session::has('success'))
      <div class="alert alert-success">
        <p>{{ Session::get('success') }}</p>
      </div><br />
     @endif
  </div>
 <div>
    {{$dataTable->table(['class' => 'table table-bordered table-striped table-hover'], true)}}
  </div>
  @push('scripts')
    {{$dataTable->scripts()}}
  @endpush
@endsection