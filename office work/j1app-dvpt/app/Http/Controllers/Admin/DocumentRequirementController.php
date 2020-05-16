<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\DocumentTypes;
use App\Models\DocumentRequirement;
use Auth;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Response;

class DocumentRequirementController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {  
        parent::__construct();
    }
    
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $admin  = Auth::user();
        $agency = Agency::select('id','agency_name')->get();
        
        $params['admin_agency_id'] = $admin->agency_id; 
        $params['agency_id'] = $request->agency_id; 
        $params['document_section'] = $request->document_section; 
        $params['document_name'] = $request->document_name; 
        $data = DocumentRequirement::filter($params)->get();
        
        $admin_agency = Agency::where('id',$admin->agency_id)->first();
        $admin_agency_type = !empty($admin_agency) ? $admin_agency->agency_type : "";
        
        if(!empty($data))
        {
            foreach($data as $key => $val)
            {
                if(!empty($val->document_template))
                {
                    $doc_template_dir ="document-template/".DS.$val->agency_id.DS;
                    $doc_template_path = $doc_template_dir.$val->document_template;
                    if(Storage::disk('public')->exists($doc_template_path)){ 
                        $dr_id = encrypt($val->id);
                        $data[$key]->download_template_link = route("download",['ddt',$dr_id]);
                    }
                }
            }
        
            $data = [
                'document_data' => $data,
                'agency_id' => $admin->agency_id,
                'agency' => $agency,
                'agency_type' => $admin_agency_type,
            ];
        }
        if($request->is('api/*')){
            return apiResponse("success","",$data);
        }
        return view('admin.document-requirement')->with($data);
    }
    
     
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $document = (object)[
                'id' => "",
                'agency_id' => "",
                'document_type' => "",
                'document_desc' => "",
                'document_template' => "",
                'requirement_type' => "",
                'document_section' => "",
                'visibility' => "",
            ];
        $agency = Agency::all();        
        $admin     = Auth::user();
        $agency_id = $admin->agency_id;
        
        $query = DocumentTypes::whereDoesntHave('documentRequirements',function (Builder $query){
                  $query->where('agency_id', '=', '0');
                });
                
        if($agency_id != 0)
        {           
            $query->whereDoesntHave('documentRequirements',function (Builder $query) use($agency_id){
                $query->where('agency_id', '=', $agency_id);
            });
        }
        $document_types = $query->get();
        
        $admin_agency = Agency::where('id',$agency_id)->first();
        $admin_agency_type = !empty($admin_agency) ? $admin_agency->agency_type : "";
        $data = [
            'document' => $document,
            'agency' => $agency,
            'agency_id' => $agency_id,
            'agency_type' => $admin_agency_type,
            'document_types' => $document_types
        ];
        return view('admin.document-requirement-add')->with($data);
    }
    
   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $doc_rules = array();
        $upload_doc_size = config('common.upload_file_size');
        $allowed_doc_size = config('common.upload_file_size') * 1000;
        $upload_doc_ext = collect(config('common.allow_doc_ext'))->implode(',');
        $allow_doc_ext = collect(config('common.allow_doc_ext'))->implode(', ');
            
        
        if(!empty($request->document_template)){ 
            $doc_rules = ['document_template' => "required|mimes:{$upload_doc_ext}|max:{$allowed_doc_size}"];
        }
            
        $rules = [
            'document_type' => "required|unique:document_requirements,document_type,NULL,id,agency_id,{$request->agency_id}",
            'document_section' => "required",
            /*'agency_id' => "required",*/
        ];

        $rules = collect($rules)->merge($doc_rules)->all();
        
        $validationErrorMessages = [
            /*'agency_id.required' => 'Agency Name field is required.',*/
            'document_type.required' => 'Document Name field is required.',
            'document_type.unique' => 'Document Type already  exists.',
            'document_section.required' => 'Document Section field is required.',
            'document_template.mimes' => "Document Template must be a file of type: {$allow_doc_ext}.",
            'document_template.required' => "Document Template is required.",
            'document_template.max' => "Document Template must be below {$upload_doc_size} MB."
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            if ($request->is('api/*')) { 
                return apiResponse("error",null,$validator->messages()->toArray(),401); 
            }
            return redirect(route('dr.add.form'))->with('errors', $validator->messages())->withInput();
        }
        
        /*upload document*/
        $document = new DocumentRequirement;
        if(!empty($request->document_template))
        {
            $doc_name = $request->document_template;
            $original_name = $doc_name->getClientOriginalName();
            $original_name_without_ext = pathinfo($original_name, PATHINFO_FILENAME);
            $ext = $doc_name->getClientOriginalExtension();   
            $filename =sanitize($original_name_without_ext).'.'.$ext;
            $folder_name = empty($request->agency_id) ? 'general-document' : $request->agency_id;
            
            $dirpath = get_upload_path("document_template",$request->agency_id);
            check_directory($dirpath);
       
            if( !$dirpath ){
                return redirect(route('dr.add.form'))->with('error', "File path not found.");
            }
            
            $doc_upload_path =  "document-template/{$request->agency_id}/";
            
            Storage::disk('public')->putFileAs(
                    $doc_upload_path,
                    $doc_name,
                    $filename
            );
             
            $document->document_template = $filename;
        }
        /*end upload document*/

        $document->agency_id = $request->agency_id;
        $document->document_desc = $request->document_desc;
        $document->visibility = $request->visibility;
        $document->document_type = $request->document_type;
        
        $document->requirement_type = $request->requirement_type;
        $document->document_section = $request->document_section;
        $document->save();
        if($request->is('api/*')){
            return apiResponse('success', "Document requirement added successfully.");
        }
        return redirect(route('dr.list'))->with('success', "Document requirement added successfully.");
    }

    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $role_name
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = decrypt($id);
        $document = DocumentRequirement::where('id', $id)->first();      
        $doc_agency_id = $document->agency_id;
        $agency = Agency::all();

        $admin     = Auth::user();
        $agency_id = $admin->agency_id;
        $admin_agency = Agency::where('id',$agency_id)->first();
        $admin_agency_type = !empty($admin_agency) ? $admin_agency->agency_type : "";  
        
        if($document->agency_id == 0)
        {
            $document_types = DocumentTypes::all();
        }
        else
        {
            $document_types = DocumentTypes::whereDoesntHave('documentRequirements',function (Builder $query){
                                $query->where('agency_id', '=', '0');
                            })->whereDoesntHave('documentRequirements',function (Builder $query) use($doc_agency_id){
                                $query->where('agency_id', '=', $doc_agency_id);
                            })->orwhereHas('documentRequirements', function (Builder $query) use($id) {
                                $query->where('id', $id);
                            })->get();
        }
        
        if(!empty($document))
        {
            if(!empty($document->document_template))
            {
                $doc_template_dir ="document-template/".DS.$document->agency_id.DS;
                $doc_template_path = $doc_template_dir.$document->document_template;
                if(Storage::disk('public')->exists($doc_template_path)){ 
                    $dr_id = encrypt($document->id);
                    $document->download_template_link = route("download",['ddt',$dr_id]);
                }
            }
            
            $data = [
                'document' => $document,
                'id' => $id,
                'agency' => $agency,
                'agency_id' => $agency_id,
                'agency_type' => $admin_agency_type,
                'document_types' => $document_types
            ];
            return view('admin.document-requirement-add')->with($data);
        }
        else{
            return redirect(route('dr.list'))->with("error","No data found");
        }
    } 
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $doc_rules = array();
        $upload_doc_size = config('common.upload_file_size');
        $allowed_doc_size = config('common.upload_file_size') * 1000;
        $upload_doc_ext = collect(config('common.allow_doc_ext'))->implode(',');
        $allow_doc_ext = collect(config('common.allow_doc_ext'))->implode(', ');
        
        $rules = [
            'document_type' => "required|unique:document_requirements,document_type,{$id},id,agency_id,{$request->agency_id}",
            'document_section' => "required",
            /*'agency_id' => "required",*/
        ];
            
        if(!empty($request->document_template)){ 
            $doc_rules = ['document_template' => "required|mimes:{$upload_doc_ext}|max:{$allowed_doc_size}"];
        }
        $rules = collect($rules)->merge($doc_rules)->all();

        $validationErrorMessages = [
            /*'agency_id.required' => 'Agency Name field is required.',*/
            'document_type.required' => 'Document Type field is required.',
            'document_type.unique' => 'Document Type already  exists.',
            'document_section.required' => 'Document Section field is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            if ($request->is('api/*')) { 
                return apiResponse("error",null,$validator->messages()->toArray(),401); 
            }
            return redirect(route('dr.edit.form',encrypt($id)))->with('errors', $validator->messages())->withInput();
        }
        
        $document = DocumentRequirement::where('id', $id)->first();
        
        /*START move document to anohter agency folder*/
        if($request->agency_id != $document->agency_id && empty($request->document_template))
        {
           Storage::disk('public')->move("document-template/{$document->agency_id}/$document->document_template", "document-template/{$request->agency_id}/$document->document_template");
        }
        /*END move document to anohter agency folder*/
        
        if(!empty($request->document_template))
        {
            $doc_name = $request->document_template;
            $original_name = $doc_name->getClientOriginalName();
            $original_name_without_ext = pathinfo($original_name, PATHINFO_FILENAME);
            $ext = $doc_name->getClientOriginalExtension();   
            $filename =sanitize($original_name_without_ext).'.'.$ext;
            
            $dirpath = get_upload_path("document_template",$request->agency_id);
            check_directory($dirpath);
            if( !$dirpath ){
                return redirect(route('dr.add.form'))->with('error', "File path not found.");
            }
            
            $doc_upload_path =  "document-template/{$request->agency_id}/";
            
            /*delete old documnet*/
                if($document->document_template != ''){
                  Storage::disk('public')->delete("document-template/{$document->agency_id}/".$document->document_template); 
                }
             
            /*end delte old document*/
                
            Storage::disk('public')->putFileAs(
                    $doc_upload_path,
                    $doc_name,
                    $filename
            );  
            
           $document->document_template = $filename;
        }
            
        $document->agency_id = $request->agency_id;
        $document->document_desc = $request->document_desc;
        $document->requirement_type = $request->requirement_type;
        $document->visibility = $request->visibility;
        $document->document_type = $request->document_type;
        $document->document_section = $request->document_section;
        $document->save();
        if($request->is('api/*')){
            return apiResponse('success', "Document requirement updated successfully.");
        }
        return redirect(route('dr.list'))->with('success', "Document requirement updated successfully.");
    }
    
    
     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        starts_with(request()->path(), 'api') ? $id = $id : $id = decrypt($id);
        if(DocumentRequirement::deleteByDocumentId($id)){
            if(starts_with(request()->path(), 'api')){
                return apiResponse("success","Document requirement deleted successfully.");
            }
            return redirect(route('dr.list'))->with("success","Document requirement deleted successfully.");
        }
        else{
            if(starts_with(request()->path(), 'api')){
                return apiResponse("error","Failed to delete document requirement.");
            }
            return redirect(route('dr.list'))->with("error","Failed to delete document requirement.");
        }
    }
    
    public function ajaxRequest(Request $request)
    {
        $action = $request->action;
        switch ($action)
        {
            case 'document_type':
                $agency_id = $request->agency_id;
                $doc_id = $request->id;
                $query = DocumentTypes::whereDoesntHave('documentRequirements',function (Builder $query){
                    $query->where('agency_id', '=', '0');
                });
                
                if($agency_id != 0)
                {
                    $query->whereDoesntHave('documentRequirements',function (Builder $query) use($agency_id){
                        $query->where('agency_id', '=', $agency_id);
                    });
                }
                
                if(!empty($doc_id))
                {
                    $query->orwhereHas('documentRequirements', function (Builder $query) use($doc_id) {
                                $query->where('id', $doc_id);
                            });
                }
                
                $document_types = $query->get();

                $data = [
                        'action' => 'document_type',
                        'document_types' => $document_types,
                    ];
                
                $HTML = view('admin.ajax')->with($data)->render();
                $response = ["type" => "success", "message" => "", "data" => $HTML];
                return Response::json($response);
            break;
        }
    }
}