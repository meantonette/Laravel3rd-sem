<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/album','AlbumController@index');

Route::get('/listener','ListenerController@index');

Route::get('/album/create','AlbumController@create')->name('album.create'); // option1

Route::post('/album/store',['uses' => 'AlbumController@store','as' => 'album.store']); //option2
Route::get('/album/edit/{id}','AlbumController@edit')->name('album.edit');

Route::post('/album/update{id}',['uses' => 'AlbumController@update','as' => 'album.update']); 

Route::get('/album/delete/{id}',['uses' => 'AlbumController@delete','as' => 'album.delete']);

Route::resource('customer', 'CustomerController');

//Route::resource("customer", CustomerController::class); //this

// Route::get('/customer/restore/{id}',['uses' => 'CustomerController@restore','as' => 'customer.restore']);

// Route::get("/customer/forceDelete/{id}", ["uses" => "CustomerController@forceDelete", "as" => "customer.forceDelete",]);

// default id 
//Kasi nga yung CRUD OR Resource AY IISA pinagsamang CREATE SHOW/READ UPDATE DELETE yan kaya no need na siya tawagin
//kaya nakahiwalay yung restore at forceDelete kasi di kasama yan sa CRUD 

// Route::resource('customer', 'CustomerController')->middleware('auth');
// Route::resource('album', 'AlbumController')->middleware('auth');
// Route::resource('artist', 'ArtistController')->middleware('auth');
// Route::resource('listener', 'ListenerController')->middleware('auth');

Route::group(['middleware' => ['auth']], function () { 
    Route::get('/customer/restore/{id}','CustomerController@restore')->name('customer.restore');
    Route::get('/customer/forceDelete/{id}', 'CustomerController@forceDelete')->name('customer.forceDelete');

	Route::resource('customer','CustomerController');
	Route::resource('album','AlbumController');
	Route::resource('artist','ArtistController');
	Route::resource('listener','ListenerController');

});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//JUNE 8======
Route::get('/listener/{search?}', [
	'uses' => 'ListenerController@index',
	 'as' => 'listener.index'
  ]);

Route::get('/artist/{search?}', [
	'uses' => 'ArtistController@index',
	 'as' => 'artist.index'
  ]);

Route::get('/album/{search?}', [
	'uses' => 'AlbumController@index',
	 'as' => 'album.index'
  ]);

Route::resource('artist', 'ArtistController')->except(['index','artist']);

Route::resource('album', 'AlbumController')->except(['index']);

Route::resource('listener', 'ListenerController')->except(['index']);

//june 9---
Route::get('/show-artist/{id}', [
	'uses' => 'ArtistController@show',
	 'as' => 'getArtist'
  ]);

  Route::get('/search/{search?}',['uses' => 'SearchController@search','as' => 'search'] );

 // june 15--
  Route::get('/artists', [
	'uses' => 'ArtistController@getArtists',
	 'as' => 'getArtists'
  ]);

// Route::get('/', function () {
//     return view('welcome');
// });


// Route::get('/db2', function() {
//     $game = new \App\Models\Game;
//     $game->name = 'Assassins Creed';
//     $game->description = 'Assassins VS templars.';
//     $game->save();

//     });


//     Route::get('/seed', function() {
//         $album = new \App\Models\Album;
//          $album->title = 'Some Mad Hope';
//          $album->artist = 'Matt Nathanson';
//          $album->genre = 'Acoustic Rock';
//          $album->year = 2007;
//          $album->save();
        
//          $album = new \App\Models\Album;
//          $album->title = 'Please';
//          $album->artist = 'Matt Nathanson';
//          $album->genre = 'Acoustic Rock';
//          $album->year = 1993;
//          $album->save();
        
//          $album = new \App\Models\Album;
//          $album->title = 'Leaving Through The Window';
//          $album->artist = 'Something Corporate';
//          $album->genre = 'Piano Rock';
//          $album->year = 2002;
//          $album->save();
        
//          $album = new \App\Models\Album;
//          $album->title = 'North';
//          $album->artist = 'Something Corporate';
//          $album->genre = 'Piano Rock';
//          $album->year = 2002;
//          $album->save();
         
//          $album = new \App\Models\Album;
//          $album->title = '...Anywhere But Here';
//          $album->artist = 'The Ataris';
//          $album->genre = 'Punk Rock';
//          $album->year = 1997;
//          $album->save();

//          $album = new \App\Models\Album;

//          $album->title = '...Is A Real Boy';
//          $album->artist = 'Say Anything';
//          $album->genre = 'Indie Rock';
//          $album->year = 2006;
//          $album->save();
//  });


//  Route::get('/album', 'AlbumController@index');

//  Route::get('/album/create', 'AlbumController@create')->name('album.create');

//  Route::post('/album/store',['uses' => 'AlbumController@store','as' => 'album.store']);

//  Route::get('/album/edit/{id}', 'AlbumController@edit')->name('album.edit');

//  Route::post('/album/update/{id}',['uses' => 'AlbumController@update','as' => 'album.update']);

//  Route::get('/album/delete/{id}',['uses' => 'AlbumController@delete','as' => 'album.delete']);

//  Route::get('/customer/restore/{id}',['uses' => 'CustomerController@restore','as' => 'customer.restore']);

Route::get('/images/customer/{filename}','CustomerController@displayImage')
->name('image.displayImage');

//  Route::resource('customer','CustomerController')->middleware('auth');
// // Route::resource('customer','CustomerController');
//  Route::resource('album','AlbumController')->middleware('auth');
//  Route::resource('artist','ArtistController')->middleware('auth');
//  Route::resource('listener','ListenerController')->middleware('auth');

             
// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class,
//  'index'])->name('home');
//  Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

// // require _DIR_.'/auth.php';

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');




