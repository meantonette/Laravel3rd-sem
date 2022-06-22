<?php

namespace App\Imports;

use App\Models\Artist;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; //kukuhain nya yung header or 1st row sa excel

class ArtistImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    //nakalagay sa array yung row, (index 0 - column 1)
    {
        return new Artist([
           'artist_name' => $row['name'],
             //'artist_name' => $row[0],
           
        ]);
    }
}
