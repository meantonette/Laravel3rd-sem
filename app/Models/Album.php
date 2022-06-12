<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;
    protected $fillable = ['album_name','artist_id','img_path'];

    public function artist() {
        return $this->belongsTo('App\Models\Artist');
   }

   public function listeners() // ! Belongs to many pag many to many
	 {
	 	return $this->belongsToMany('App\Models\Listener');
	 }
}
