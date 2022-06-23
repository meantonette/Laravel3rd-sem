<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Album;
use \App\Models\artist;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\DataTables\AlbumsDataTable;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AlbumImport;
use App\Imports\AlbumArtistListenerImport;
use App\rules\excelrule;

class AlbumController extends Controller
{
    public function index(Request $request)
    { 
        //Lesson
        //Relationships ex 1 to many, many - many
        //June 8 lesson -using like, search
        if (empty($request->get('search'))) {
            $albums = Album::with('artist','listeners')->get();
        }
        else { 
        $albums = Album::whereHas('artist', function($q) use($request) {
            $q->where("artist_name","LIKE", "%".$request->get('search')."%");
    })->orWhereHas('listeners', function($q) use($request){
      $q->where("listener_name","LIKE", "%".$request->get('search')."%");
    })->orWhere('album_name',"LIKE", "%".$request->get('search')."%")
    ->get();
 }

 $url = 'album';
 return View::make('album.index',compact('albums','url'));

    }


    public function create()
    {
       // return view::make('album.create');

        $artists = Artist::pluck('artist_name','id');
        //lahat ng artist kukunin ^^ 

        return View::make('album.create',compact('artists'));
        //kukunin yung artist
    }

    public function store(Request $request)
    {
        // $input = $request->all();

        $artist = Artist::find($request['artist_id']);
        // dd($artist);
        $album = new Album();
        $album->album_name = $request->album_name;
        $album->artist()->associate($artist);
        // ! Associate pag gusto ng relational without inner join
        // ? Use associate only for children not for parent
    
        $request->validate([
            'image' => 'mimes:jpeg,png,jpg,gif,svg',
        ]);

        if ($file = $request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $destinationPath = public_path() . '/images';
            $input['img_path'] = 'images/' . $fileName;
            $file->move($destinationPath, $fileName);
        }
        $album->save();

        return Redirect::to('/album')->with('success', 'New Album added!');
        //2ndsemcode
    }

    public function show($id)
    {
        $albums = album::all();
        return View::make('album.index',compact('albums'));
    }

    public function edit($id)
    {

        $album = Album::find($id);

        // ! Eager Loading when using where and first.
        //=====june 2
        $album = Album::with('artist')->where('id', $id)->first();
        // $album = Album::with('artist')->find($id)->first(); 
        // ! Don't use find in relationship.

        // $albums = Album::with('artist')->where('id',$id)->take(1)->get();
        // dd($album,$albums);
        //$artist = Artist::where('id',$album->artist_id)->pluck('name','id');
        // dd($album);
        $artists = Artist::pluck('artist_name', 'id');
         //artist pluck kasi array yung id. isa isa kaya hindi select all gamit

	    return View::make('album.edit',compact('album', 'artists'));
    }



    public function update(Request $request, $id)
    {

    //dd($request);
    //$album = Album::find($request->id);
    //  $album = Album::find($id);
    //  //dd($album,$request->all());
    //  //dd($album,$request);
    //  //dd($album);
    //  $album->update($request->all());

        $artist = Artist::find($request->artist_id);
        // dd($artist);
        $album = Album::find($id);
        $album->album_name = $request->album_name;
        $album->artist()->associate($artist);
        $album->save();

        return Redirect::to('/album')->with('success', 'Album updated!');
    }

    public function destroy($id)
    {
        $album = Album::find($id);
        $album->listeners()->detach();
        $album->delete();
        
        return Redirect::route('album.index')->with('success','Album deleted!');
        //old code
    }

    public function getAlbums(AlbumsDataTable $dataTable)
    {   
       
        $albums =  Album::with(['artist','listeners'])->get();

        return $dataTable->render('album.albums');

    }

   
public function import(Request $request) {
        
    $request->validate([
       'album_upload' => ['required', new ExcelRule($request->file('album_upload'))],
   ]);
   // dd($request);
   // Excel::import(new AlbumImport, request()->file('album_upload')); //AlbumImport
Excel::import(new AlbumArtistListenerImport, request()->file('album_upload')); //FirstSheetImport
   
   return redirect()->back()->with('success', 'Excel file Imported Successfully');
}
    
}
