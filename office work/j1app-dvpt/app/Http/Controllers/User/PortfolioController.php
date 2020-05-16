<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Portfolio; 

class PortfolioController extends Controller
{
    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {  
        parent::__construct();
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $portfolio = Portfolio::where('user_id',$request->user->id)->orderBy('id', 'DESC')->get()->all();
        $portfolio_arr = collect($portfolio)->toArray();
        $portfolio_status = array_column($portfolio_arr, 'portfolio_status');
        $data = [
            'portfolio' => $portfolio,
            'portfolio_status' => $portfolio_status,
        ];
        if($request->is('api/*')){
            return apiResponse("success","",$data);
        }
        return view('user.portfolio-listing')->with($data);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewPortfolioDetail($p_no)
    {
        $portfolio = Portfolio::where('portfolio_number',$p_no)->first();
        $data = [
            'portfolio' => $portfolio,
        ];
        if(starts_with(request()->path(), 'api')){
            return apiResponse("success","",$data);
        }
        return view('user.portfolio-detail')->with($data);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createPortfolio(Request $request)
    {
        $portfolio_arr = collect(Portfolio::where('user_id',$request->user['id'])->get()->all())->toArray();
        $portfolio_status_arr = array_column($portfolio_arr, 'portfolio_status');
        if(!empty(array_intersect([0,1,2], (array)$portfolio_status_arr))){ 
            if($request->is('api/*')){
           return apiResponse('error', 'Already exist your portfolio.');
        }
            return redirect(route('myportfolio'))->with('error', 'Already exist your portfolio.'); 
        }
        
        $portfolio = new Portfolio;
        $portfolio->user_id = $request->user->id;
        $portfolio->portfolio_number = "PF".mt_rand(100000000, 999999999);
        $portfolio->save();
        
        /* Update portfolio id in users table */
        $user = $request->user;
        $user->portfolio_id = $portfolio->id;
        $user->save();  
        if($request->is('api/*')){
           return apiResponse('success', 'Your portfolio has been created successfully.');
        }
        return redirect(route('myportfolio'))->with('success', 'Your portfolio has been created successfully.');
    }
}
