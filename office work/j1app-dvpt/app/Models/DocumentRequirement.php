<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Storage;
use App\Models\DocumentTypes;

class DocumentRequirement extends Model
{
    protected $table = "document_requirements";
    public $primaryKey = "id";
    
     /**
    * document_requirements table filter
    *
    * @return array
    */
    public function scopeFilter($query, $params)
    {    
        $query->select('document_requirements.id' ,
                    'document_requirements.agency_id',
                    'document_requirements.document_desc',
                    'document_requirements.document_template',
                    'document_requirements.requirement_type',
                    'document_requirements.document_section',
                    'document_requirements.visibility',
                    'agency.agency_name',
                    'document_types.name as document_type'); 
        $query->leftjoin('agency','agency.id', '=', 'document_requirements.agency_id');
        $query->leftjoin('document_types','document_types.id', '=', 'document_requirements.document_type');
        
        if ( isset($params['admin_agency_id']) && !empty($params['admin_agency_id']) ){
            $query->where('document_requirements.agency_id', '=', trim($params['admin_agency_id']));
        }
        if ( isset($params['agency_id']) && !empty($params['agency_id']) ){
            $query->where('document_requirements.agency_id', '=', trim($params['agency_id']));
        }
        if ( isset($params['document_section']) && trim($params['document_section']) !== '' ){
            $query->where('document_requirements.document_section', '=', trim($params['document_section']));
        }
        if ( isset($params['document_name']) && trim($params['document_name']) !== '' ){
            $query->where('document_types.name', 'LIKE', '%'.trim($params['document_name']).'%');
        }
        return $query;
    }
    
    public static function deleteByDocumentId($id)
    {
        /*delete document from the folder*/
        $document = DocumentRequirement::where('id', $id)->first();

        if($document->document_template != ''){
            Storage::disk('public')->delete("document-template/{$document->agency_id}/$document->document_template"); 
        }
        
        return DB::table('document_requirements')->where('id', $id)->delete();
    }
    
    public function documentType(){
        return $this->belongsTo('App\Models\DocumentTypes','document_type');
    }
    
    /*
     * $doc_section = 1 Basic Document
     * $doc_section = 2 Post Placement Document
     * 
     */
    public function getDocumentByDocSection(User $user, $doc_section = null, $agency_id = null, $checkVisibility = true)
    {
        $app_interface = config('common.app_interface');
        $auto_admin = config('common.auto_admin');
        if(!empty($user)){
            $portfolio_id = $user->portfolio->id;
            $user_id = $user->id;
            
            $query = self::select('dt.name as document_label', 
                                'dt.id as document_type_id',
                                'document_desc as doc_desc',
                                'document_requirements.id as document_requirement_id',
                                'document_requirements.agency_id',
                                'document_requirements.document_template',
                                'document_requirements.visibility',
                                'document_requirements.requirement_type',
                                DB::raw("CONCAT(IF(d.uploaded_by_user != 0,by_user.first_name,IF(d.uploaded_by_admin != 0,by_admin.first_name,'')),' ',IF(d.uploaded_by_user != 0,by_user.last_name,IF(d.uploaded_by_admin != 0,by_admin.last_name,''))) AS uploaded_by"),
                                DB::raw("IF(d.action_by_id != {$auto_admin},"
                                        . "CONCAT(IF(d.action_by_id != 0,action_by.first_name,''),' ',IF(d.action_by_id != 0,action_by.last_name,'')),"
                                        . "'Auto Admin') AS action_by"),
                                'd.id as document_id',
                                'd.document_filename',
                                DB::raw('IF(d.document_status = 1,"Approved",IF(d.document_status = 2,"Rejected","Pending")) AS document_status_name'),
                                'd.created_at as document_uploaded',
                                'd.updated_at as document_status_date',
                                'd.document_status',
                                DB::raw("(SELECT COUNT(id) "
                                        . "FROM documents "
                                        . "WHERE portfolio_id = '{$portfolio_id}' "
                                        . "AND user_id = '{$user_id}' "
                                        . "AND document_type = document_requirements.document_type) as document_count_by_type")
                            )
                        ->leftJoin('document_types AS dt', 'dt.id', 'document_type')
                        ->leftJoin('documents as d', function($join) use($user_id, $portfolio_id){
                            $join->on('d.document_type', 'dt.id')
                                ->whereRaw("d.id IN (SELECT MAX(d2.id) "
                                        . "FROM documents AS d2 "
                                        . "JOIN document_types AS dt ON dt.id = d2.document_type "
                                        . "WHERE d2.portfolio_id = {$portfolio_id} "
                                        . "GROUP BY dt.id)");
                            })
                        ->leftJoin('admins as by_admin','by_admin.id','d.uploaded_by_admin')
                        ->leftJoin('users as by_user','by_user.id','d.uploaded_by_user')
                        ->leftJoin('admins as action_by','action_by.id','d.action_by_id');
                            
            if(!empty($agency_id)){
                $query->whereIn('document_requirements.agency_id',[0,$agency_id]);
            }
            else{
                $query->where('document_requirements.agency_id',0);
            }
            
            if(!empty($doc_section)){
                $query->where('document_section', $doc_section);
            }
            
            if($checkVisibility == true){
                if(!empty($app_interface == "user")){
                    $query->whereIn('visibility', [2,3]);
                }
                elseif(!empty($app_interface == "admin")){
                    $query->whereIn('visibility', [1,3]);
                }
            }
             
            $query->groupBy('dt.id');
            
            $data = $query->get()->all();
            if(!empty($data)){
                $upload_dir_path = config("common.user-documents").DS.$user_id.DS;
                
                foreach($data as $key => $val){
                    if(!empty($val->document_id))
                    {
                        $file_name = $val->document_filename;
                        $file_path = $upload_dir_path.$file_name;

                        if(Storage::disk('public')->exists($file_path))
                        {
                            $doc_id = encrypt($val->document_id);
                            $data[$key]->document_download_link = route("download",['dd',$doc_id]);;
                        }
                    }
                    
                    if(!empty($val->document_template))
                    {
                        $doc_template_dir ="document-template/".DS.$val->agency_id.DS;
                        $doc_template_path = $doc_template_dir.$val->document_template;
                        if(Storage::disk('public')->exists($doc_template_path)){ 
                            $dr_id = encrypt($val->document_requirement_id);
                            $data[$key]->download_template_link = route("download",['ddt',$dr_id]);
                        }
                    }
                }
            }
            
            $document_requirements = $data;
            
            $data = Document::documentCounts($user,$doc_section);
            $data['total_document_requirements'] = count($document_requirements);
            $data['document_requirements'] = $document_requirements;
 
            return (!empty($data))? $data : false;
        }
    }
    
    /*
     * $doc_section = 1 Basic Document
     * $doc_section = 2 Post Placement Document
     * 
     */
    public function getRequiredDocBySec(User $user, $doc_section = null, $agency_id = null)
    { 
        $app_interface = config('common.app_interface');
        if(!empty($user)){
            $portfolio_id = $user->portfolio->id;
            $user_id = $user->id;
            
            $query = self::select(DB::raw('count(id) as doc_req_count'))
                    ->where('requirement_type', 1);
                        
            if(!empty($agency_id)){
                $query->whereIn('document_requirements.agency_id',[0,$agency_id]);
            }
            else{
                $query->where('document_requirements.agency_id',0);
            }
            
            if(!empty($doc_section)){
                $query->where('document_section', $doc_section);
            }
             
            if($checkVisibility == true){
                if(!empty($app_interface == "user")){
                    $query->whereIn('visibility', [2,3]);
                }
                elseif(!empty($app_interface == "admin")){
                    $query->whereIn('visibility', [1,3]);
                }
            } 
            $data = $query->first();
             
            $req_count = $data->doc_req_count;
            
            $data = Document::documentCounts($user,$doc_section);
            $data['req_count'] = $req_count;
            
            return (!empty($data))? $data : false;
        }
    }
}