<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Timezone; 
use App\Models\Document;
use App\Models\DocumentRequirement;

class CommonController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function localTimezone(Request $request)
    {
        $offset = $request->get('offset',"");
        $offset_hrs = $request->get('offset_hrs',"");
        $dst = $request->get('dst',"");
        
        if (is_numeric($offset_hrs) && is_numeric($dst))
        {
            $tz = new Timezone;
            $local_timezone = $tz->detectTimezoneId($offset_hrs, $dst);
            if(!empty($local_timezone) && $local_timezone != "UTC")
            {
                session([
                    'local_timezone' => $local_timezone->zone_id,
                    'local_timezone_name' => $local_timezone->zone_name,
                    'local_timezone_offset' => $offset
                ]);
                
                $response = [
                    'local_timezone' => $local_timezone->zone_name,
                    'local_timezone_offset' => $offset,
                ];
            }
            else
                $response = ['type' => 'error', 'message' => "Failed to detect local timezone"];
        }
        
        return $response;
    }
    
    public function errorPage() {
        return view('UserInterface.errors.404');
    }
    
    public function downloadDocument(Request $request){
        
        $action = $request->action;
        $value = $request->value;
        
        if(!empty($action)){
            switch($action){
                case "dd":
                    $doc_id = decrypt($value);
                    if(!empty($doc_id)){
                        $doc_data = Document::where('id', $doc_id)->first();
                        if(!empty($doc_data) && !empty($doc_data->document_filename))
                        {
                            $doc_path = "user-documents".DS.$doc_data->user_id.DS.$doc_data->document_filename; 
                            $doc_label = (!empty($doc_data->documentType->name)) ? $doc_data->documentType->name : "";
                            if(!empty($doc_path)){ 
                                return $this->downloadFile($doc_path, $doc_label);
                            }
                        }
                    }
                break;
            
                case "ddt":
                    $doc_req_id = decrypt($value);
                    $doc_req_data = DocumentRequirement::where('id', $doc_req_id)->first();
                    if(!empty($doc_req_data) && !empty($doc_req_data->document_template))
                    {  
                        $doc_template_dir ='document-template'.DS.$doc_req_data->agency_id.DS; 
                        $doc_template_path = $doc_template_dir.$doc_req_data->document_template;
                        $doc_template_label = (!empty($doc_req_data->documentType->name)) ? $doc_req_data->documentType->name : "";
                        if(!empty($doc_template_path)){ 
                            return $this->downloadFile($doc_template_path, $doc_template_label);
                        }
                    }
                break;
            
                default:
                break;
            }
        }
        
        return false;
    }
}
