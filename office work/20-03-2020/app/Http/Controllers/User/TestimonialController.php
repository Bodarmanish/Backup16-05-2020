<?php


namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use App\Models\Testimonial;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {         
        $testimonial_data = collect(Testimonial::where('status', 1)->get())->all();
        
        if ($request->is('api/*')) {
           return apiResponse("success","",$testimonial_data);
        }
        
        return view('user.testimonial',compact('testimonial_data'));
    }
}
