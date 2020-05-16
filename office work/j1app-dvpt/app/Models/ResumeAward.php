<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class ResumeAward extends Model
{
    protected $table = "resume_award";
    
    protected $fillable = [
        'title','description','award_date'
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
