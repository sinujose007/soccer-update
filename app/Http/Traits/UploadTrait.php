<?php

namespace App\Http\Traits;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait UploadTrait
{
    public function uploadOne(UploadedFile $uploadedFile, $folder = null, $disk = 'public', $filename = null)
    {
        $name = !is_null($filename) ? $filename : Str::random(25);
		$filename = $name.'.'.$uploadedFile->getClientOriginalExtension();
		$uploadedFile->move($folder, $filename);	
		return $filename;
    }
	
	public function deleteOne($folder = null, $playerImageURI = null)
	{	   
	    if($playerImageURI !='' ) {
			$fnamearray = explode("/",$playerImageURI);
			$lengthar = count($fnamearray);
			$filename = $fnamearray[$lengthar-1];
		}
		if(file_exists($folder.$filename)) {
		   unlink($folder.$filename);
		}
		return $filename;
	}
}