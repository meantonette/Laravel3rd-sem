<?php

use Illuminate\Support\Facades\Route;

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

 Route::resource('customer','CustomerController')->middleware('auth');
// Route::resource('customer','CustomerController');
 Route::resource('album','AlbumController')->middleware('auth');
 Route::resource('artist','ArtistController')->middleware('auth');
 Route::resource('listener','ListenerController')->middleware('auth');

             
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class,
 'index'])->name('home');





 Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// require _DIR_.'/auth.php';

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');




