<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;


class AlbumController extends Controller
{
    public function index()
    { // TODO: Always use eager loading for relational database.
        // $albums = album::all(); // ! No relations when dumping unlike with.
        // $albums = Album::with('artist')->orderBy('album_name', 'DESC')->get();
        $albums = Album::with('artist', 'listeners')->get();
        // ! using with is eager loading 
        // ? Difference of eager and lazy loaded is simple eager loading doesn't repeat the same id
        foreach ($albums as $album) {
            // dump($album->artist);
            // dump($album->artist->artist_name); // !this is lazy loaded
            // dump($album->listeners);
            foreach ($album->listeners as $listener) {
                dump($listener->listener_name);
            }
        }

        return View::make('album.index', compact('albums'));
    }
    public function create()
    {
        // return View::make('album.create');
        $artists = Artist::pluck('artist_name', 'id');
        return View::make('album.create', compact('artists'));
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
    }

    public function edit($id)
    {

        // ! Eager Loading when using where and first.
        $album = Album::with('artist')->where('id', $id)->first();
        // $album = Album::with('artist')->find($id)->first(); 
        // ! Don't use find in relationship.
        $artists = Artist::pluck('artist_name', 'id');
        return View::make('album.edit', compact('album', 'artists'));
    }



    public function update(Request $request, $id)
    {
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
        //   $album->listeners
        $album->delete();

        return Redirect::to('/album')->with('success', 'Album deleted!');
    }
}
