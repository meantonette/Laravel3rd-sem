<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use Redirect;
use App\Models\Album;
use App\Models\Artist;
use DB;


class AlbumController extends Controller
{
    public function index()
    {
        // $albums = album::all();
        // dd(compact('albums'));
        // $albums = DB::table('artists')->join('albums','artists.id','=','albums.artist_id')->get();
        // $albums = Album::with('artist')->orderBy('album_name', 'DESC')->get();


        $albums = Album::with('artist','listeners')->get();
        //     // dd(compact('albums'));
        //     $albums = DB::table('artists')->join('albums','artists.id','=','albums.artist_id')->get();
    
        //     return View::make('album.index', compact('albums'));
        // 
    
        foreach ($albums as $album) {
            // dump($album);
            // dump($album->artist->artist_name); // this is lazy loaded
            // dump($album->listeners); 
            foreach ($album->listeners as $listener) {
                // dump($listener->listener_name); 
               
            }
           }
    
        $albums = Album::with('artist')->orderBy('album_name', 'DESC')->get();
    
        // foreach($albums as $album){
            // dump($album);
            // dump($album->artist->artist_name); //this is lazy loaded
    
        // } 
        // foreach ($albums as $album) {
        //     dump($album->album_name);
        //  dump($album->artist->artist_name); // this is lazy loaded
        // }
    
             return View::make('album.index', compact('albums'));

       




    }
        public function create() {
            // return View::make('album.create');
            $artists = Artist::pluck('artist_name','id');
     return View::make('album.create',compact('artists'));
    }

    public function store(Request $request) {
        // dd($request);
        // $title = $request->title;
        // $artist = $request->artist;
        // $genre = $request->genre;
        // $year = $request->year;

        // $album = New Album;
        // $album->title = $title;
        // $album->artist = $artist;
        // $album->genre = $genre;
        // $album->year = $year;
        // $album->save();
        // return Redirect::to('/album');
        
        

        $input = $request->all();
       
        $request->validate([
            'image' => 'mimes:jpeg,png,jpg,gif,svg',
        ]);

        if($file = $request->hasFile('image')) {
            $file = $request->file('image') ;
            // $fileName = uniqid().'_'.$file->getClientOriginalName();
            $fileName = $file->getClientOriginalName();
            $destinationPath = public_path().'/images' ;
            // dd($fileName);
        $input['img_path'] = 'images/'.$fileName;
        // $album = Album::create($input);
            $file->move($destinationPath,$fileName);
        }
        $album = Album::create($input);
        return Redirect::to('/album')->with('success','New Album added!');

       }

       public function edit($id) {
	    // $album = Album::find($id);
	    // dd($album);
        // $album = Album::find($id);
	    // $artists = Artist::pluck('artist_name','id');


        $album = Album::with('artist')->where('id',$id)->first();
        // $album = Album::with('artist')->find($id)->first();
        // $albums = Album::with('artist')->where('id',$id)->take(1)->get();
        // dd($album,$albums);
        //$artist = Artist::where('id',$album->artist_id)->pluck('name','id');
        // dd($album);
        $artists = Artist::pluck('artist_name','id');
	    return View::make('album.edit',compact('album', 'artists')); 
    }



    public function update(Request $request, $id){
        // dd($request);
        // $album = Album::find($id);
        // dd($album,$request->all());
        // $album = New Album;
        // $album->update($request->all());

        // $album = Album::find($id);
       //  // dd($album);
       //  $album->update($request->all());
       //  return Redirect::route('album.index')->with('success','Album updated!');

       $artist = Artist::find($request->artist_id);
       // dd($artist);
       $album = Album::find($id);
       $album->album_name = $request->album_name;
       $album->artist()->associate($artist);
       $album->save();

        return Redirect::to('/album')->with('success','Album updated!');
       }




   
       public function destroy($id)
       {
          $album = Album::find($id);
        //   $album->listeners
          $album->delete();

        return Redirect::to('/album')->with('success','Album deleted!');

       }
}
