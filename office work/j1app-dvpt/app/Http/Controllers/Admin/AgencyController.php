<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\AgencyContract;
use Validator;
use Response;
use App\Models\User;
use App\Models\UserLog;

class AgencyController extends Controller
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
    public function index()
    {
        $data = collect(Agency::all())->all();

        if(!empty($data))
        {
            $data = [
                'agency_data' => $data
            ];
        }
        if (starts_with(request()->path(), 'api')) {
            return apiResponse("success","",$data);
        }
        return view('admin.agencies')->with($data);
    }
    
         
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $agency = (object)[
                'id' => "",
                'agency_name' => "",
                'agency_type' => "",
                'agency_address' => "",
                'description' => "",
                'status' => "",
            ];
        
        $data = [
            'agency' => $agency,
        ];
        return view('admin.agencyadd')->with($data);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'agency_name' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/',
             'agency_type' => "required",
        ];

        $validationErrorMessages = [
            'agency_name.required' => 'Agency Name field is required.',
            'agency_name.regex' => 'Agency Name does not allow any special character.',
            'agency_type.required' => 'Agency Type field is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            if ($request->is('api/*')) { 
                return apiResponse("error",null,$validator->messages()->toArray(),401); 
            }
            return redirect(route('agency.add.form'))->with('errors', $validator->messages())->withInput();
        }

        $agency = new Agency;
        $agency->agency_name = $request->agency_name;
        $agency->description = $request->description;
        $agency->status = $request->status;
        $agency->agency_address = $request->agency_address;
        $agency->agency_type = $request->agency_type;
        $agency->save();
        if($request->is('api/*')){
            return apiResponse('success', "Agency added successfully.");
        }
        return redirect(route('agency.list'))->with('success', "Agency added successfully.");
    }

    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $role_name
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $agency = Agency::where('id', $id)->first();
    
        if(!empty($agency))
        {
            $data = [
                'agency' => $agency,
                'id' => $id
            ];
            return view('admin.agencyadd')->with($data);
        }
        else{
            return redirect(route('agency.list'))->with("error","No data found");
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
        $rules = [
            'agency_name' => "required|regex:/(^[A-Za-z0-9 ]+$)+/",
            'agency_type' => "required",
        ];

        $validationErrorMessages = [
            'agency_name.required' => 'Role Name field is required.',
            'agency_name.regex' => 'Role Name does not allow any special character.',
            'agency_type.required' => 'Agency Type field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            if ($request->is('api/*')) { 
                return apiResponse("error",null,$validator->messages()->toArray(),401); 
            }
            return redirect(route('agency.edit.form'))->with('errors', $validator->messages())->withInput();
        }
        
        
        $agency = Agency::where('id', $id)->first();;
       
        $agency->agency_name = $request->agency_name;
        $agency->description = $request->description;
        $agency->status = $request->status;
        $agency->agency_address = $request->agency_address;
        $agency->agency_type = $request->agency_type;
        $agency->save();
        if ($request->is('api/*')) { 
            return apiResponse('success', "Agency updated successfully."); 
        }
        return redirect(route('agency.list'))->with('success', "Agency updated successfully.");
    }
    
    
     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Agency::deleteByAgencyId($id)){
            if(starts_with(request()->path(), 'api')){
                return apiResponse("success","Agency deleted successfully."); 
            }
            return redirect(route('agency.list'))->with("success","Agency deleted successfully.");
        }
        else{
            if(starts_with(request()->path(), 'api')){
                return apiResponse("error","Failed to delete agency.");
            }
            return redirect(route('agency.list'))->with("error","Failed to delete agency.");
        }
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function agencyContractAction(Request $request){
        
        $btn_action = $request->get('btn_action',"");
        $agency_contract = $request->get('agency_contract',"");
        $request->is('api/*') ? $agency_contract = $agency_contract : $agency_contract = decrypt($agency_contract);
        $contract = AgencyContract::where('id',$agency_contract)->where('is_expired', 0)->first();
        
        if(!empty($agency_contract) && !empty($contract) && !empty($contract->user_id)){
            
            $user = User::where('id',$contract->user_id)->first();
            $portfolio = $user->portfolio;
            
            if($btn_action == "accept"){
                $user->setAgency($contract->id);
                
                if($contract->contract_type == 2 || $contract->contract_type == 4){
                    $status = "placement-contract";
                    $contract_type = ($contract->contract_type == 4)?[2,4]:[2];
                }
                elseif($contract->contract_type == 3 || $contract->contract_type == 4){
                    $status = "sponsor-contract";
                    $contract_type = ($contract->contract_type == 4)?[3,4]:[2];
                }

                $this->changeUserStatus($user, $status);
                
                $agencyContracts = $portfolio->agencyContracts();
                $agencyContracts->where('is_expired', 0)
                        ->where('request_status', 1)
                        ->whereIn('contract_type', $contract_type)
                        ->update(['is_expired' => 1]);

                UserLog::log($user,"contract-accepted");

                return ['type' => "success", 'message' => "Agency contract accepted successfully."];
            }
            elseif($btn_action == "reject"){
                $contract->update(['request_status' => 3, 'is_expired' => 1]);
                UserLog::log($user,"contract-rejected");

                return ['type' => "success", 'message' => "Agency contract rejected successfully."];
            }
            else{
                $response = ['type' => "error", 'message' => "Agency contract action not found."];
            }
        }
        else{
            $response = ['type' => "error", 'message' => "Agency contract does not found."];
        }
        
        return Response::json($response);
    }
    
    public function contractList(Request $request){
        
        if(!empty(auth()->user()->agency_id)){
            $agency_id = auth()->user()->agency_id;
            $contract_query = AgencyContract::where('agency_id',$agency_id)->with('agency','user');
        }
        else{
            $contract_query = AgencyContract::with('agency','user');
        }
        
        $params = $request->except('_token');

        if(!empty($params)){
            $contracts = $contract_query->filter($params)->get();
            $params['contract_data'] = $contracts;
            
            return view('admin.contract-list')->with($params);
        }
        else{
            $contracts = $contract_query->get();
            $data = [
                'contract_data' => $contracts,
            ];
            if($request->is('api/*')){
                return apiResponse("success","",$data);
            }
            return view('admin.contract-list')->with($data);
        }
    }
    
}