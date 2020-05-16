<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use DB;

class Resume extends Model
{
    protected $table = "resume";
    
    public function awards(){
        return $this->hasMany('App\Models\ResumeAward','resume_id');
    }
    
    public function certificates(){
        return $this->hasMany('App\Models\ResumeCertificate','resume_id');
    }
    
    public function education(){
        return $this->hasMany('App\Models\ResumeEducation','resume_id');
    }
    
    public function employment(){
        return $this->hasMany('App\Models\ResumeEmployment','resume_id');
    }
    
    public function updateResume(User $user,$field_data)
    {
        if(!empty($user) && !empty($field_data))
        {
            if(!empty($user->resume)){
                $resume = $user->resume;
            }
            else{
                $resume = new Resume;
                $resume->user_id = $user->id;
            }
            
            foreach($field_data as $field_name => $field_value)
            {
                $resume->$field_name = (!empty($field_value)) ? $field_value : "";
            }
            
            $resume->save();
            
            return (!empty($resume->id))? $resume->id : false;
        }
        else
            return false;
    }
    
    public function updateEducation(User $user, $field_data, $education_id = ""){
        
        if(!empty($user->resume) && !empty($field_data))
        {
            $education = $user->resume->education();
            
            if(!empty($education_id)){
                
                $employment_row = $education->where("id",$education_id)->first();
                if(!empty($employment_row)){
                    foreach($field_data as $field_name => $field_value)
                    {
                        $employment_row->$field_name = (!empty($field_value)) ? $field_value : "";
                    }
                    $employment_row->save();
                    return $employment_row;
                }
                else{
                    return $education->create($field_data);
                }
            }
            else{
                return $education->create($field_data);
            }
        }
        else
            return false;
    }
    
    public function removeEducation(User $user, $remove_id){
        if(!empty($user->resume) && !empty($remove_id))
        {
            $education = $user->resume->education();
            
            if(is_array($remove_id))
                return $education->whereIn('id', $remove_id)->delete();
            else
                return $education->where('id', $remove_id)->delete();
        }
        else{
            return false;
        }
    }
    
    public function updateEmployment(User $user, $field_data, $employment_id = ""){
        
        if(!empty($user->resume) && !empty($field_data))
        {
            $employment = $user->resume->employment();
            
            if(!empty($employment_id)){
                
                $employment_row = $employment->where("id",$employment_id)->first();
                if(!empty($employment_row)){
                    foreach($field_data as $field_name => $field_value)
                    {
                        $employment_row->$field_name = (!empty($field_value)) ? $field_value : "";
                    }
                    $employment_row->save();
                    return $employment_row;
                }
                else{
                    return $employment->create($field_data);
                }
            }
            else{
                return $employment->create($field_data);
            }
        }
        else
            return false;
    }
    
    public function removeEmployment(User $user, $remove_id){
        if(!empty($user->resume) && !empty($remove_id))
        {
            $employment = $user->resume->employment();
            
            if(is_array($remove_id))
                return $employment->whereIn('id', $remove_id)->delete();
            else
                return $employment->where('id', $remove_id)->delete();
        }
        else{
            return false;
        }
    }
    
    public function updateCertificate(User $user, $field_data, $certificate_id = ""){
        
        if(!empty($user->resume) && !empty($field_data))
        {
            $certificates = $user->resume->certificates();
            
            if(!empty($certificate_id)){
                
                $certificate_row = $certificates->where("id",$certificate_id)->first();
                if(!empty($certificate_row)){
                    foreach($field_data as $field_name => $field_value)
                    {
                        $certificate_row->$field_name = (!empty($field_value)) ? $field_value : "";
                    }
                    $certificate_row->save();
                    return $certificate_row;
                }
                else{
                    return $certificates->create($field_data);
                }
            }
            else{
                return $certificates->create($field_data);
            }
        }
        else
            return false;
    }
    
    public function removeCertificate(User $user, $remove_id){
        if(!empty($user->resume) && !empty($remove_id))
        {
            $certificates = $user->resume->certificates();
            
            if(is_array($remove_id))
                return $certificates->whereIn('id', $remove_id)->delete();
            else
                return $certificates->where('id', $remove_id)->delete();
        }
        else{
            return false;
        }
    }
    
    public function updateAward(User $user, $field_data, $award_id = ""){
        
        if(!empty($user->resume) && !empty($field_data))
        {
            $awards = $user->resume->awards();
            
            if(!empty($award_id)){
                
                $award_row = $awards->where("id",$award_id)->first();
                if(!empty($award_row)){
                    foreach($field_data as $field_name => $field_value)
                    {
                        $award_row->$field_name = (!empty($field_value)) ? $field_value : "";
                    }
                    $award_row->save();
                    return $award_row;
                }
                else{
                    return $awards->create($field_data);
                }
            }
            else{
                return $awards->create($field_data);
            }
        }
        else
            return false;
    }
    
    public function removeAward(User $user, $remove_id){
        if(!empty($user->resume) && !empty($remove_id))
        {
            $awards = $user->resume->awards();
            
            if(is_array($remove_id))
                return $awards->whereIn('id', $remove_id)->delete();
            else
                return $awards->where('id', $remove_id)->delete();
        }
        else{
            return false;
        }
    }
    
    
}
