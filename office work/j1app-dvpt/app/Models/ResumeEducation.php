<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class ResumeEducation extends Model
{
    protected $table = "resume_education";
    
    protected $fillable = [
        'school','degree','minor','description','start_date','end_date'
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
