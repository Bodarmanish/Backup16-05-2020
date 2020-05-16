<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Storage;

class Testimonial extends Model
{
    protected $table = "testimonials";
    public $primaryKey = "id";
    
    
    public static function deleteBytesTimonialId($id){
        
        /*delete Testimonial image from folder*/
        Storage::disk('public')->deleteDirectory('testimonial/'.$id);
        
        return DB::table('testimonials')->where('id', $id)->delete();
    }    
    
}
