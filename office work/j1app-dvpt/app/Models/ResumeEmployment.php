<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class ResumeEmployment extends Model
{
    protected $table = "resume_employment";
    
    protected $fillable = [
        'title','employer_name','duties','location','start_date','end_date'
    ];

    public $timestamps = false;
    
    public function removeById($remove_id)
    {
        if(is_array($remove_id))
            return DB::table($this->table)->whereIn('id', $remove_id)->delete();
        else
            return DB::table($this->table)->where('id', $remove_id)->delete();
    }
}
