<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
Use App\Models\artist;
Use App\Models\album;
Use App\Models\listener;

class ListenerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //old code & June 8 code
        if (empty($request->get('search'))) {
            $listeners = Listener::with('albums')->get();
        }
    
        else {

            $listeners = Listener::whereHas('albums', function($q) use($request){
                $q->where("album_name","LIKE", "%".$request->get('search')."%")
                  ->orWhere("album_name","LIKE", "%".$request->get('search')."%");
                  })->orWhere('listener_name',"LIKE", "%".$request->get('search')."%")
              ->get();
          }
      
          $url = 'listener';
      
         return View::make('listener.index',compact('listeners','url'));
        }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // checkbox gagamitin kasi maraming pagpipilian kaya PLUCK
        // $albums = Album::pluck('album_name','id');
        // // dd($albums);
        // return View::make('listener.create',compact('albums'));

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

        //old and newcode

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
        //Old & new code, june 2
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
        //Old code

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
        $listener = Listener::find($id);
        $listener->albums()->detach();
        // DB::table('album_listener')->where('listener_id',$id)->delete();
        
        $listener->delete();
     //   return Redirect::route('listener')->with('success','listener deleted!');
        return Redirect::to('listener.index')->with('success','New listener deleted!');
    }
}
