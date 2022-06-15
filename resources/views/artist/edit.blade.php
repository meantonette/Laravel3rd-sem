{{-- using form method --}}
@extends('layouts.app')
@extends('layouts.app')
@section('content')
 <div class="container">
      <h2>Edit Artist</h2><br/>
      {{-- dd($artists) --}}
      {{ Form::model($artist,['route' => ['artist.update',$artist->id],'method'=>'PUT']) }}
<div class="row">
          <div class="col-md-4"></div>
          <div class="form-group col-md-4">
            <label for="Name">artist Name:</label>
           {!! Form::text('artist_name',$artist->artist_name,array('class' => 'form-control')) !!}
            {{--name ng text field ^^ --}}
          </div>
        </div>
{{-- <div class="row"> --}}
          {{-- <div class="col-md-4"></div>
            <div class="form-group col-md-4">
              <label for="artist">artist:</label>
              {!! Form::select('artist_id',$artists, $artist->artist_id,['class' => 'form-control']) !!}
           {{-- form select, 2nd para - lahat ng array of artists, 3rd para- yun yung nakahighlight/nakaselect 
                kaagad sa dropbox at hindi sya id--}}
            {{-- </div> --}}
      </div>
 </div>
        <div class="row">
          <div class="col-md-4"></div>
          <div class="form-group col-md-4" style="margin-top:60px">
            <button type="submit" class="btn btn-success">Submit</button>
          </div>
        </div>
     {!! Form::close() !!}
    </div>
@endsection

{{-- old version --}}