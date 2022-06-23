<?php

namespace App\Imports;

// use Illuminate\Support\Collection;
// use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AlbumArtistListenerImport implements  WithMultipleSheets

{
    	public function sheets(): array
	    {
	        return [
	           // 'albums' => new FirstSheetImport(),
	            //^^ name ng sheet 
                'sample' => new FirstSheetImport(),
	        ];
	    }
}
