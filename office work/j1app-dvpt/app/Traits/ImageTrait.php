<?php

namespace App\Traits;

use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use File;

trait ImageTrait
{
    public function uploadImage($fileinfo, $image_upload_path){
        
        if(!empty($fileinfo))
        {  
            $manager = new ImageManager();
            $image = $manager->make( $fileinfo )
                    ->encode('jpg');
            $normal_stream = $image->stream();
            Storage::disk('public')->put($image_upload_path, $normal_stream->__toString());
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function cropImage($fileinfo, $image_upload_path, $imgW=300, $imgH=300, $cropW=300, $cropH=300, $imgX1=300, $imgY1=300, $angle=0){
        
        if(!empty($fileinfo))
        { 
            $manager = new ImageManager();
            $image_crop = $manager->make( $fileinfo )
                ->resize($imgW, $imgH)
                ->rotate(-$angle)
                ->crop($cropW, $cropH, $imgX1, $imgY1);
            $crop_stream = $image_crop->stream();
            Storage::disk('public')->put($image_upload_path, $crop_stream->__toString());
            
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function createThumbImage($image_path,$target_file_path = "",$thumb_size = [50,200])
    {
        $allowed_image_extension = config('common.allow_image_ext');
        if(!empty($image_path))
        {
            $file_extension = File::extension($image_path);
            if(in_array($file_extension,$allowed_image_extension))
            {
                $manager = new ImageManager();
                if(is_array($thumb_size)){
                    foreach($thumb_size as $size)
                    {
                        if(is_numeric($size) && $size > 0)
                        {
                            $new_filepath = (!empty($target_file_path))?$target_file_path:generate_thumb_path($image_path,$size);
                            if(!empty($new_filepath))
                            {
                                $image = $manager->make($image_path)
                                        ->resize($size, $size, function( $constraint ) {
                                            $constraint->aspectRatio();
                                        });
                                $normal_stream = $image->stream();
                                Storage::disk('public')->put($new_filepath, $normal_stream->__toString());
                            }
                        }
                    }
                    return true;
                }
                else if(is_numeric($thumb_size)){
                    $new_filepath = (!empty($target_file_path))?$target_file_path:generate_thumb_path($image_path,$thumb_size);

                    if(!empty($new_filepath))
                    {
                        $image = $manager->make($image_path)
                                ->resize($thumb_size, $thumb_size, function( $constraint ) {
                                    $constraint->aspectRatio();
                                });
                        $normal_stream = $image->stream();
                        Storage::disk('public')->put($new_filepath, $normal_stream->__toString());    
                        return true;
                    }
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
     
    
    public function getStoreFileName($fileinfo){
        if(!empty($fileinfo))
        {
            $original_name = $fileinfo->getClientOriginalName();
            $original_name_without_ext = pathinfo($original_name,PATHINFO_FILENAME);
            $filename = sanitize($original_name_without_ext);
            $allowed_filename = createUniqueFilename( $filename );
            $store_filename = $allowed_filename .'.jpg';
            return $store_filename;
        }
        else
        {
            return false;
        }
    }
}