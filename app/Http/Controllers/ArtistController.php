<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
Use App\Models\artist;
Use App\Models\album;
Use App\DataTables\ArtistsDataTable;
use Illuminate\Support\Facades\DB;
use Laravel\Ui\Presets\React;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Facades\DataTables;

class ArtistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * //2ndsemcode
     */
    public function index(Request $request)
    {
        
        //$artists = Artist::with('albums')->orderBy('artist_name', 'DESC')->get();
        // dd($artists);
        // dump($artists);
        // foreach ($artists as $artist) {
        //     dump($artist);
        //     dump($artist->artist_name);
        //     dump($artist->albums); // ! lazy loaded with relationship one to many
        //     foreach ($artist->albums as $album) {
        //         dump($album->album_name);
        //     }
        // }

 ///JUNE 8============
 if (empty($request->get('search'))) {
    // $artists = Artist::with('albums')->get();
    $artists = Artist::has('albums')->get();
    //ifefetech yung may related album

    // dd($artists);
}

else 

$artists = Artist::with(['albums' => function($q) use($request){
    $q->where("genre","=",$request->get('search'))
        ->orWhere("album_name","LIKE", "%".$request->get('search')."%");
    }])->where("artist_name","LIKE", "%".$request->get('search')."%")
    ->get();

    $url = 'artist';
    return View::make('artist.index',compact('artists','url'));
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View::make('artist.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     //old code
    public function store(Request $request)
    {
       
        $input = $request->all();
        // dd($input);
        Artist::create($input);

        return Redirect::to('artists');
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
        $artists = Artist::all();
        return View::make('artist.index',compact('artists'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        // $artist = Artist::find($id);
        // return View::make('artist.edit', compact('artist'));

        $artist = Artist::find($id);
        // dd($artist);
        return View::make('artist.edit',compact('artist'));
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
        // $artist = Artist::find($id);

        // $artist->update($request->all());
        // return Redirect::to('/artist')->with('success', 'Artist updated!');

        $artist = Artist::find($id);
        // $album->artist_id = $request->artist_id;
        $artist->artist_name = $request->artist_name;
        $artist->save();
        return Redirect::to('/artists')->with('success','Artist updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       
        $artist = Artist::find($id);
           // Album::where('artist_id',$artist->id)->delete();
              //old code
        $artist->albums()->delete();
        $artist->delete();
        $artist = Artist::with('albums')->get();
      
        // ! Delete artist and album same time 

        return Redirect::to('/artists');
    }

    // public function getArtists(ArtistsDataTable $dataTable) {
    //     // dd($dataTable);
    //     return $dataTable->render('artist.artists');
    // }

    public function getArtists(Builder $builder) {

        //other methods
        $artists = Artist::query();
//eloquent builder

      if (request()->ajax()) {

            return DataTables::of($artists)
                    //     ->order(function ($query) {
                    //  $query->orderBy('updated_at', 'DESC');
                    //  }) //permanent order na sya ng column, di mo magagamit yung sort arrow. naooverride
             ->addColumn('action', function($row) {
                    return "<a href=". route('artist.edit', $row->id). " class=\"btn btn-warning\">Edit</a>
                    <form action=". route('artist.destroy', $row->id). " method= \"POST\" >". csrf_field() .
                    '<input name="_method" type="hidden" value="DELETE">
                    <button class="btn btn-danger" type="submit">Delete</button>
                      </form>';
                    })
                    //->rawColumns(['action'])
                     // ->make(true);
                      ->toJson();
         }
        $html = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'Id'],
            ['data' => 'artist_name', 'name' => 'artist_name', 'title' => 'Name'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At'],
            ['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Updated At'],
            ['data' => 'action', 'name' => 'action', 'title' => 'Action', 'searchable' => false, 'orderable' => false, 'exportable' => false]
//name - name sa table, title-name ng header
            // ['data' => 'action', 'name' => 'action', 'title' => 'Action']
        ]);
return view('artist.artists', compact('html'));
}
}