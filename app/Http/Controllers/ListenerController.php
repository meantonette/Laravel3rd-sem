<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Listener;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ListenerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listeners = DB::table('listeners')
            ->leftJoin('album_listener', 'listeners.id', '=', 'album_listener.listener_id')
            ->leftJoin('albums', 'albums.id', '=', 'album_listener.album_id')
            ->select('listeners.id', 'listeners.listener_name', 'albums.album_name')
            ->get();
        return View::make('listener.index', compact('listeners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $albums = Album::with('artist')->get();
        // dump($albums->artist->artist_name);  // ? needs to be loop if error is collection
        // foreach ($albums as $album) {
        //     dump($album->artist->artist_name);
        // }
        return View::make('listener.create', compact('albums'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $input = $request->all();
        // dd($request->album_id);
        $listener = Listener::create($input);
        if (!(empty($request->album_id))) {
            foreach ($request->album_id as $album_id) {
                // DB::table('album_listener')->insert(
                //     ['album_id' => $album_id, 
                //      'listener_id' => $listener->id]
                //     );
                // dd($listener->albums());
                $listener->albums()->attach($album_id);
            } // ? attach para di na need tawagin isang table aattach n lang table agad mismo
        }
        return Redirect::to('listener')->with('success', 'New listener added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $listener_albums = array();
        $listener = Listener::with('albums')->where('id', $id)->first();
        if (!(empty($listener->albums))) {
            foreach ($listener->albums as $listener_album) {
                $listener_albums[$listener_album->id] = $listener_album->album_name;
            }
        }
        $albums = Album::pluck('album_name', 'id')->toArray();

        return View::make('listener.edit', compact('albums', 'listener', 'listener_albums'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $listener = Listener::find($id);
        $album_ids = $request->input('album_id');
        // dd($album_ids);

        //  if(empty($album_ids)){
        //      $listener->albums()->detach();
        //  }
        //  else {
        // foreach($album_ids as $album_id) {
        //      $listener->albums()->detach();

        //      $listener->albums()->attach($album_ids);
        // }
        //  }

        $listener->albums()->sync($album_ids);


        $listener->update($request->all());


        return Redirect::route('listener.index')->with('success', 'lister updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Listener = Listener::find($id);
        $Listener->albums()->detach();
        $Listener->delete();

        return Redirect::route('listener.index')->with('success', 'Listener deleted!');
    }
}
