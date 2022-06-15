@extends('layouts.base')
@extends('layouts.app')
@section('content')

<div class="container">
<table class="table table-striped">
    <thead>
      <tr>
        <th>D</th>
        <th>Artist name</th>
        </tr>
    </thead>
    <tbody>
        <tr>
        <td>{{$artist->id}}</td>
        <td>{{$artist->artist_name}}</td>
        </tr>
     
    </tbody>
  </table>
</div>
</div>
@endsection