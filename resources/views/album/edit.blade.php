{{-- using form method --}}

@extends('layouts.base')
@extends('layouts.app')
@section('content')
 <div class="container">
      <h2>Edit Album</h2><br/>
      {{-- dd($artists) --}}
      {{ Form::model($album,['route' => ['album.update',$album->id],'method'=>'PUT']) }}
<div class="row">
          <div class="col-md-4"></div>
          <div class="form-group col-md-4">
            <label for="Name">Album Name:</label>
           {!! Form::text('album_name',$album->album_name,array('class' => 'form-control')) !!}
            {{--name ng text field ^^ --}}
          </div>
        </div>
<div class="row">
          <div class="col-md-4"></div>
            <div class="form-group col-md-4">
              <label for="artist">artist:</label>
              {!! Form::select('artist_id',$artists, $album->artist_id,['class' => 'form-control']) !!}
           {{-- form select, 2nd para - lahat ng array of artists, 3rd para- yun yung nakahighlight/nakaselect 
                kaagad sa dropbox at hindi sya id--}}
            </div>
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

{{-- old code --}}
