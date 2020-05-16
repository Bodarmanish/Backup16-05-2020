<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HostCompany;
use Validator;
use Response;

class HostCompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        
        $hc = new HostCompany;
        $host_companies = $hc->getHostCompanies();
        
        $data = [
            'host_companies' => $host_companies
        ];
        if(starts_with(request()->path(), 'api')){
            return apiResponse("success","",$data);
        }
        return view('admin.hostcompanies')->with($data);
    }
    
    public function create(){
        
        $host_company = (object) [
            'id' => "",
            'hc_name' => "",
            'hc_state' => "",
        ];
        
        $data = [
            'host_company' => $host_company,
        ];
        
        return view('admin.hcadd')->with($data);
    }
    
    public function store(Request $request){
        
        $rules = [
            'hc_name' => 'required|unique:host_companies,hc_name',
            'hc_id_number' => 'required|unique:host_companies,hc_id_number',
            'contact_email' => "nullable|email",
            'contact_website' => "nullable|url",
        ];

        $validationErrorMessages = [
            'hc_name.required' => "Host Company Name field is required.",
            'hc_name.unique' => "Host Company Name has already been taken.",
            'hc_id_number.required' => "Host Company Id Number field is required.",
            'hc_id_number.unique' => "Host Company Id Number has already been taken.",
            'contact_email.email' => "Contact Email is invalid.",
            'contact_website.url' => "Contact Website url is invalid.",
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            if ($request->is('api/*')){
                return apiResponse("error", "", $validator->messages()->toArray());
            }
            return redirect(route('hc.add.form'))->with('errors', $validator->messages())->withInput();
        }
        
        $hc = new HostCompany;
        $hc->hc_name = $request->hc_name;
        $hc->hc_id_number = $request->hc_id_number;
        $hc->hc_description = $request->hc_description;
        $hc->hc_street = $request->hc_street;
        $hc->hc_suite = $request->hc_suite;
        $hc->hc_city = $request->hc_city;
        $hc->hc_state = $request->hc_state;
        $hc->hc_zip = $request->hc_zip;
        $hc->contact_first_name = $request->contact_first_name;
        $hc->contact_last_name = $request->contact_last_name;
        $hc->contact_title = $request->contact_title;
        $hc->contact_email = $request->contact_email;
        $hc->contact_skype = $request->contact_skype;
        $hc->contact_phone = $request->contact_phone;
        $hc->contact_phone_extension = $request->contact_phone_extension;
        $hc->contact_fax = $request->contact_fax;
        $hc->contact_website = $request->contact_website;
        $hc->created_by = auth()->user()->id;
        $hc->save();
        if ($request->is('api/*')) {
            return apiResponse('success', "Host company added successfully.");
        }
        return redirect(route('hc.list'))->with('success', "Host company added successfully.");
    }
    
    public function edit($id){
        
        $host_company = HostCompany::where('id', $id)->first();
        
        $data = [
            'id' => $id,
            'host_company' => $host_company,
        ];
        
        return view('admin.hcadd')->with($data);
    }
    
    public function update(Request $request, $id){
        
        $hc_id_number = $request->hc_id_number;
        
        $rules = [
            'hc_name' => "required|unique:host_companies,hc_name,{$id},id",
            'hc_id_number' => "unique:host_companies,hc_id_number,{$id},id",
            'contact_email' => "nullable|email",
        ];

        $validationErrorMessages = [
            'hc_name.required' => "Host Company Name field is required.",
            'hc_name.unique' => "Host Company Name has already been taken.",
            'hc_id_number.required' => "Host Company Id Number field is required.",
            'hc_id_number.unique' => "Host Company Id Number has already been taken.",
            'contact_email.email' => "Contact Email is invalid.",
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            if ($request->is('api/*')){
                return apiResponse("error", "", $validator->messages()->toArray());
            }
            return redirect(route('hc.edit.form'))->with('errors', $validator->messages())->withInput();
        }
        
        $hc = HostCompany::where('id',$id)->first();
        $hc->hc_name = $request->hc_name;
        $hc->hc_id_number = $request->hc_id_number;
        $hc->hc_description = $request->hc_description;
        $hc->hc_street = $request->hc_street;
        $hc->hc_suite = $request->hc_suite;
        $hc->hc_city = $request->hc_city;
        $hc->hc_state = $request->hc_state;
        $hc->hc_zip = $request->hc_zip;
        $hc->contact_first_name = $request->contact_first_name;
        $hc->contact_last_name = $request->contact_last_name;
        $hc->contact_title = $request->contact_title;
        $hc->contact_email = $request->contact_email;
        $hc->contact_skype = $request->contact_skype;
        $hc->contact_phone = $request->contact_phone;
        $hc->contact_phone_extension = $request->contact_phone_extension;
        $hc->contact_fax = $request->contact_fax;
        $hc->contact_website = $request->contact_website;
        $hc->created_by = auth()->user()->id;
        $hc->save();
        if ($request->is('api/*')) {
            return apiResponse('success', "Host company updated successfully.");
        }
        return redirect(route('hc.list'))->with('success', "Host company updated successfully.");
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(HostCompany::deleteById($id)){
            if(starts_with(request()->path(), 'api')){
                return apiResponse("success","Host company deleted successfully.");
            }
            return redirect(route('hc.list'))->with("success","Host company deleted successfully.");
        }
        else{
            if(starts_with(request()->path(), 'api')){
                return apiResponse("error","Failed to delete Host Company.");
            }
            return redirect(route('hc.list'))->with("error","Failed to delete Host Company.");
        }
    }
    
    public function updateStatus(Request $request){
        
        $inputs = $request->all();
        if(!empty($inputs)){
            
            if($inputs['action'] == "update_hc_status_form" && !empty($inputs['id'])){
                $id = $inputs['id'];
                $hc = HostCompany::where('id',$id)->select('id','status')->first();
                
                $data = [
                    'action' => "update_hc_status_form",
                    'hc' => $hc,
                ];
                
                $html = view('admin.ajax')->with($data)->render();
                
                return apiResponse("success","",$html);
            }
            else if($inputs['action'] == "update_hc_status"){
                
                $id = $inputs['id'];
                $hc_status = $inputs['hc_status'];
                
                $hc = HostCompany::where('id',$id)->first();
                $hc->status = $hc_status;
                $hc->save();
                if ($request->is('api/*')){
                    return apiResponse("success","Host company status updated successfully.");
                }
                return apiResponse("success","Host company status updated successfully.");
            }
        }
        else{
            return apiResponse("error","Invalid inputs.");
        }
    }
    
      /**
     * Display a HC detail of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function detail($id) {
        $host_company = HostCompany::where('id', decrypt($id))->first();
        
        $data = [
            'id' => $id,
            'host_company' => $host_company,
        ];
        
        return view('admin.hcdetail')->with($data);
    }
    
    public function ajaxRequest(Request $request)
    {
        $action = $request->action;
        switch ($action)
        {
            case 'validate_EIN':
                
                if($request->mode == 'Add')
                {
                    $rules = [
                        'hc_id_number' => 'unique:host_companies,hc_id_number'
                    ];
                }
                else
                { 
                    $rules = [
                        'hc_id_number' => "unique:host_companies,hc_id_number,{$request->id},id"
                    ];
                }

                $validationErrorMessages = [
                    'hc_id_number.unique' => "Host Company Id Number has already been taken."
                ];

                $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

                if ( $validator->fails())
                {
                    $response = ["type" => "error", "message" => $validator->messages()->toArray()];
                    return Response::json($response);
                }
                    
                $response = ["type" => "success", "message" => 'Valid EIN number'];
                return Response::json($response);
            break;
        }
    }
}
