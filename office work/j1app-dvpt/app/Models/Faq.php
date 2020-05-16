<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Faq extends Model
{
    protected $table = "faq_master";
    
    public function __construct(array $attributes = array()) {
        parent::__construct($attributes);
        
    }
    /**
    * Function getFaqs()
    * This function for get faq list
     */
    public function getFaqs($faq_id = NULL,$request_data = Null){
        
        $query = DB::table('faq_master')
               ->select('faq_master.*')->orderBy('faq_order','asc');                   
        
        if(!empty($faq_id)){
            $query->where("faq_master.id","=",$faq_id);
        }       
        $faqs = $query->get();
        
        if(!empty($faqs))
        {
            return $faqs;   
        }
        else
        {
            return false;
        }
    
    }
    /**
    * Function deleteByFaqId()
    * This function for delete faq by id
     */
    public static function deleteByFaqId($id){
        return DB::table('faq_master')->where('id', $id)->delete();
    }
}
