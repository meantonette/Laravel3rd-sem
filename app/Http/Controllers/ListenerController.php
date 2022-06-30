<?php

namespace App\Http\Controllers;

 //old code & June 8 code-index
    //old and newcode-store
         //Old & new code, june 2-edit
   //Old code-update

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
Use App\Models\artist;
Use App\Models\album;
Use App\Models\listener;
use App\DataTables\ListenersDataTable;
use PDF;
use Barryvdh\Snappy;
use Dompdf\Dompdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ListenerImport;
use App\rules\excelrule;
use App\Events\SendMail;
use Illuminate\Support\Facades\Event;

class ListenerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
   
    {
        
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

     //june 29
        $input = $request->all();
        $input['password'] = bcrypt($request->password);
        $listener = Listener::create($input);
        Event::dispatch(new SendMail($listener));
        if(!(empty($request->album_id))){
                $listener->albums()->attach($request->album_id);
          }
        return Redirect::route('getListeners')->with('success','listener created!');
        
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
       //pwede gumamit ng laravel fire

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
        return Redirect::to('listeners')->with('success','New listener deleted!');
    }

    public function getListeners(ListenersDataTable $dataTable)
    {
        //eager loaded albums
        $albums = Album::with('artist')->get();
        return $dataTable->render('listener.listeners', compact('albums'));
    }

    public function import(Request $request) {
        
        $request->validate([
                'listener_upload' => ['required', new ExcelRule($request->file('listener_upload'))],
        ]);
        // dd($request);
        Excel::import(new listenerImport, request()->file('listener_upload'));
return redirect()->back()->with('success', 'Excel file Imported Successfully');
    }
}
