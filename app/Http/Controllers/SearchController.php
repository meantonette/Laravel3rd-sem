<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\Models\artist;
Use App\Models\album;
Use App\Models\listener;
use Spatie\Searchable\Search;

class SearchController extends Controller
{
    //
    public function search(Request $request){
    	// dd($request);
    	$searchResults = (new Search())
		   ->registerModel(Artist::class, 'artist_name')
		   ->registerModel(Album::class, 'album_name', 'genre')
			->registerModel(Listener::class, 'listener_name')
		   ->search($request->get('search'));
		   // dd($searchResults);
	   // return view('item.search',compact('searchResults'));
		   return view('search',compact('searchResults'));
    }
}
