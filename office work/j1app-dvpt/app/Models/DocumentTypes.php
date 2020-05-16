<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentTypes extends Model 
{
    protected $table = "document_types";
    public $primaryKey = "id";

    public static function getKeyById($id)
    {
        if(!empty($id)){
            $data = self::where('id',$id)->select('doc_key')->first();
            if(!empty($data)){
                return $data->doc_key;
            }
        }
        return false;
    }
    
    public static function getIdByKey($key)
    {
        if(!empty($key)){
            $data = self::where('doc_key',$key)->select('id')->first();
            if(!empty($data)){
                return $data->id;
            }
        }
        return false;
    }
    
    public function documentRequirements(){
        return $this->hasMany('App\Models\DocumentRequirement','document_type');
    }
}
