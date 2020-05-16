<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use File;
use finfo;
use Illuminate\Http\UploadedFile;

trait DocumentTrait
{
    /**
     * @param  \Illuminate\Http\File|\Illuminate\Http\UploadedFile  $file
     * @param string $destination_path store uploaded file to this path
     * @param string $filename [optional] file name without extension
     * **/
    public function uploadFile($file, $destination_path, $filename = null){
        
        if(!empty($file) && !empty($destination_path))
        {
            $ext = $file->getClientOriginalExtension();
            
            if(!empty($filename)){
                $filename = $filename.'.'.$ext;
            }
            else{
                $filename = generateFileName($file);
            }
            
            Storage::disk('public')->putFileAs(
                $destination_path,
                $file,
                $filename
            );
            
            return $filename;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * @param string $source_file_path full path of uploaded file
     * @param string $destination_path store uploaded file to this path
     * @param string $filename [optional] file name without extension
     * **/
    public function moveFile($source_file_path, $destination_path, $filename = null){
        
        if(file_exists($source_file_path) && is_file($source_file_path)){
            
            $fileinfo = pathinfo($source_file_path);
            $org_name = basename($source_file_path);
            
            $ext = $fileinfo['extension'];
            
            if(!empty($filename)){
                $filename = $filename.'.'.$ext;
            }
            else{
                $filename = $org_name;
            }
            
            $destination_file_path = $destination_path.DS.$filename;
            
            $fileContent = $this->getUploadedFile($source_file_path);
            
            Storage::disk('public')->putFileAs(
                $destination_path,
                $fileContent,
                $filename
            );
            
            File::delete($source_file_path);
            
            return $filename;
        }
        else{
            return false;
        }
    }
    
    public function generateFileName($file){
        if(!empty($file))
        {
            $ext = $file->getClientOriginalExtension();
            $unique_filename = createUniqueFilename("file", 10);
            $filename = sanitize($unique_filename).'.'.$ext;
            return $filename;
        }
        else
        {
            return false;
        }
    }
    
    public function getUploadedFile($source_file_path){
        
        if(file_exists($source_file_path) && is_file($source_file_path)){
            
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $filename = basename($source_file_path);

            return new UploadedFile(
                $source_file_path,
                $filename,
                $finfo->file($source_file_path),
                filesize($source_file_path),
                0,
                false
            );
        }
    }
    
    public function downloadFile($source_file_path, $custom_filename = NULL){ 
        if(!empty($source_file_path) && !empty(Storage::disk('public')->exists($source_file_path))){
            
            $fileinfo = pathinfo($source_file_path);
            $mtype = Storage::disk('public')->mimeType($source_file_path);
            $fsize = Storage::disk('public')->size($source_file_path);
            $name = $fileinfo['filename'];
            $extension = $fileinfo['extension'];
            
            if(!empty($custom_filename)){
                $name = pathinfo($custom_filename,PATHINFO_FILENAME);
            }
            
            $filename = str_slug($name).".{$extension}";

            $headers = array('Content-Type' => $mtype);
            
            return Storage::disk('public')->download($source_file_path, $filename, $headers);
        }
    }
}