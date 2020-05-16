<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Storage;

class Document extends Model
{
    protected $table = "documents";
    
    public function getDocumentByType(User $user, $doc_key){
        if(!empty($user)){
            
            $portfolio = $user->portfolio;
            
            $data = Document::with('documentType:id,name,doc_key')
                        ->whereHas('documentType',function(\Illuminate\Database\Eloquent\Builder $query) use($doc_key){
                            $query->where('doc_key', $doc_key);
                        })
                        ->select('id','user_id','document_type','document_filename','document_status','document_reject_reason','created_at')
                        ->where('user_id',$user->id)
                        ->where('portfolio_id',$portfolio->id)
                        ->orderBy('id',"DESC")                                
                        ->first();
            
            if(!empty($data)){
                $data = $this->prepareDownloadLink($data,$user);
            }
            
            return (!empty($data)) ? $data : false;
        }
        else{
            return false;
        }
    }
    
    public function prepareDownloadLink(Document $document,User $user){
        
        $upload_dir_path = config("common.user-documents").DS.$user->id.DS;
        if(!empty($document->id))
        {
            $file_name = $document->document_filename;
            $file_path = $upload_dir_path.$file_name;
            if(Storage::disk('public')->exists($file_path))
            {
                $doc_id = encrypt($document->id);
                $document->document_download_link = route("download",['dd',$doc_id]);
            }
        }
        
        return $document;
    }
    
    public function deleteDocumentById(User $user, $doc_id)
    { 
        $document = DB::table('documents')->select('document_filename')->where('id', $doc_id);
                 
        if(!empty($document->document_filename)){
            Storage::disk('public')->delete("documents/{$user->id}/$document->document_filename"); 
        }
        return DB::table('documents')->where('id', $doc_id)->delete();
    } 
    
    public function documentType(){
        return $this->belongsTo('App\Models\DocumentTypes','document_type');
    }
    
    public static function documentCounts(User $user, $doc_section = null){
        if(!empty($user)){
            $portfolio = $user->portfolio;
            $portfolio_id = $portfolio->id;
            
            $agency_id = "";
            if($doc_section == 1){
                $agency_id = $portfolio->placement_agency_id;
            }
            elseif($doc_section == 2){
                $agency_id = $portfolio->sponsor_agency_id;
            }
            
            $query = self::select('documents.document_status', 
                        DB::raw("COUNT(documents.id) AS document_count"),
                        DB::raw("SUM((CASE WHEN "
                                . "dr.requirement_type = 1 "
                                . "AND dr.document_type = documents.document_type "
                                . "AND documents.document_status = 1 "
                                . "THEN 1 ELSE 0 END)) AS approved_req_count")
                    )
                    ->leftJoin('document_requirements AS dr',function($join) use($agency_id) {
                        $join->on('dr.document_type','documents.document_type')
                            ->where(function ($query) use($agency_id) {
                                $query->where('dr.agency_id', '=', 0)
                                      ->orWhere('dr.agency_id', '=', $agency_id);
                            });
                    })
                    ->where('documents.portfolio_id',$portfolio_id)
                    ->where('dr.document_section',$doc_section)
                    ->groupBy('documents.document_status')
                    ->orderBy('documents.document_status');
            
            $data = $query->get();
            
            $temp_arr = [
                'uploaded_documents' => 0,
                'approved_documents' => 0,
                'approved_req_count' => 0,
                'rejected_documents' => 0,
            ];
            if(!empty($data)){
                foreach($data as $value){
                    if($value->document_status == 0){
                        $temp_arr['uploaded_documents'] = $value->document_count;
                    }
                    else if($value->document_status == 1){
                        $temp_arr['approved_documents'] = $value->document_count;
                        $temp_arr['approved_req_count'] = $value->approved_req_count;
                    }
                    else if($value->document_status == 2){
                        $temp_arr['rejected_documents'] = $value->document_count;
                    }
                }
                
                return $temp_arr;
            }
            
            $query = DB::table('document_requirements')
                    ->where();
        }
        return false;
    }
}
