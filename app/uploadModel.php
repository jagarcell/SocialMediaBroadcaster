<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class uploadModel extends Model
{
    //
    public function fileUpload($file)
    {
    	# code...
    	Storage::put('images', $file);
    }
}
