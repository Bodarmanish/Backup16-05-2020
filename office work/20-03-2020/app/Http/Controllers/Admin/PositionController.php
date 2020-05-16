<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Position;
use App\Models\HostCompany;
use Carbon\Carbon;
use DB;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        
        $query = DB::table('host_company_positions AS hcp');
        $query->join('host_companies AS hc','hc.id','hcp.hc_id');
        $query->join('admins AS created_by_admin','created_by_admin.id', 'hcp.created_by');
        
        $columns = [
            'hcp.id',
            'hcp.hc_id',
            'hcp.pos_name',
            'hcp.start_date',
            'hcp.end_date',
            'hc.hc_name',
            'agency.agency_name',
            DB::raw("CONCAT(created_by_admin.first_name,' ',created_by_admin.last_name) AS created_by"),
        ];
        
        if($user->role_name == "agency-admin"){
            
            $query->join('agency', function ($join) use($user) {
                    $join->on('agency.id', '=', 'created_by_admin.agency_id')
                         ->where('agency.id', '=', $user->agency_id);
                    });

        }
        else{
            $query->leftJoin('agency', 'agency.id', 'created_by_admin.agency_id');
        }
        
        $positions = $query->select($columns)->get();
        $positions = collect($positions)->all();
        
        $data = [
            'positions' => $positions,
        ];
        
        if (request()->is('api/*')) {
            return apiResponse("success","",$data);
        }
        else{
            return view('admin.positions')->with($data);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $host_companies = collect(HostCompany::select('id','hc_name')->where('status',1)->orderBy('hc_name','ASC')->get())->all();
        
        $position = (object) [
            'id' => "",
            'hc_id' => "",
            'pos_name' => "",
            'pos_description' => "",
            'start_date' => "",
            'end_date' => "",
            'no_of_openings' => "",
            'status' => "",
            'salary' => "",
            'pay_rate_basis' => "",
            'tips' => "",
            'is_housing' => "",
            'housing_description' => "",
        ];
        
        $data = [
            'position' => $position,
            'host_companies' => $host_companies,
        ];
        
        return view('admin.positionadd')->with($data);
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
            'hc_id' => 'required',
            'pos_name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required|date|after:start_date',
        ];

        $validationErrorMessages = [
            'hc_id.required' => "Host Company field is required.",
            'pos_name.required' => "Position Name field is required.",
            'start_date.required' => "Start Date field is required.",
            'end_date.required' => "End Date field is required.",
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            if ($request->is('api/*')){
                return apiResponse("error", "", $validator->messages()->toArray());
            }
            return redirect(route('hc.pos.add.form'))->with('errors', $validator->messages())->withInput();
        }
        
        $pos = new Position;
        $pos->hc_id = $request->hc_id;
        $pos->pos_name = $request->pos_name;
        $pos->pos_description = $request->pos_description;
        $pos->start_date = Carbon::parse($request->start_date)->format(DB_DATE_FORMAT);
        $pos->end_date = Carbon::parse($request->end_date)->format(DB_DATE_FORMAT);
        $pos->no_of_openings = $request->no_of_openings;
        $pos->status = $request->status;
        $pos->salary = $request->salary;
        $pos->pay_rate_basis = $request->pay_rate_basis;
        $pos->tips = $request->tips;
        $pos->is_housing = $request->is_housing;
        $pos->housing_description = $request->housing_description;
        $pos->created_by = auth()->user()->id;
        $pos->save();
        
        if (request()->is('api/*')) {
            $data = [
                'pos_id' => $pos->id,
                'pos_name' => $pos->pos_name,
            ];
            return apiResponse("success","Position added successfully.",$data);
        }
        else{
            return redirect(route('hc.pos.list'))->with('success', "Position added successfully.");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $position = new Position;
        if(!$position->checkPositionAccess($id)){
            return redirect(route('hc.pos.list'))->with('error', "Invalid parameter or data not found.");
        }
            
        $position_data = Position::where('id',$id)->first();

        $host_companies = HostCompany::select('id','hc_name')->where('status',1)->orderBy('hc_name','ASC')->get();
        $host_companies = collect($host_companies)->all();

        $data = [
            'id' => $id,
            'position' => $position_data,
            'host_companies' => $host_companies,
        ];

        return view('admin.positionadd')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $position = new Position;
        if(!$position->checkPositionAccess($id)){
            return redirect(route('hc.pos.list'))->with('error', "Invalid parameter or data not found.");
        }
        
        $rules = [
            'hc_id' => 'required',
            'pos_name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required|date|after:start_date',
        ];

        $validationErrorMessages = [
            'hc_id.required' => "Host Company field is required.",
            'pos_name.required' => "Position Name field is required.",
            'start_date.required' => "Start Date field is required.",
            'end_date.required' => "End Date field is required.",
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            if ($request->is('api/*')){
                return apiResponse("error", "", $validator->messages()->toArray());
            }
            return redirect(route('hc.pos.edit.form'))->with('errors', $validator->messages())->withInput();
        }
        
        $pos = Position::where('id',$id)->first();
        $pos->hc_id = $request->hc_id;
        $pos->pos_name = $request->pos_name;
        $pos->pos_description = $request->pos_description;
        $pos->start_date = Carbon::parse($request->start_date)->format("Y-m-d");
        $pos->end_date = Carbon::parse($request->end_date)->format("Y-m-d");
        $pos->no_of_openings = $request->no_of_openings;
        $pos->status = $request->status;
        $pos->salary = $request->salary;
        $pos->pay_rate_basis = $request->pay_rate_basis;
        $pos->tips = $request->tips;
        $pos->is_housing = $request->is_housing;
        $pos->housing_description = $request->housing_description;
        $pos->created_by = auth()->user()->id;
        $pos->save();
        if ($request->is('api/*')){
            return apiResponse('success', "Position updated successfully.");
        }
        return redirect(route('hc.pos.list'))->with('success', "Position updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $position = new Position;
        if(!$position->checkPositionAccess($id)){
            if(starts_with(request()->path(), 'api')){
                return apiResponse('error', "Invalid parameter or data not found.");
            }
            return redirect(route('hc.pos.list'))->with('error', "Invalid parameter or data not found.");
        }
        
        if(Position::deleteById($id)){
            if(starts_with(request()->path(), 'api')){
                return apiResponse("success","Position deleted successfully.");
            }
            return redirect(route('hc.pos.list'))->with("success","Position deleted successfully.");
        }
        else{
            if(starts_with(request()->path(), 'api')){
                return apiResponse("error","Failed to delete position.");
            }
            return redirect(route('hc.pos.list'))->with("error","Failed to delete position.");
        }
    }

}
