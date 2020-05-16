<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\Models\Faq;

class PagesController extends Controller
{ 
    /**
     * Home page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
        $testimonial_data = collect(Testimonial::where('status', 1)->take(10)->get())->all();
        $faq_data = Faq::where('status', 1)->orderBy('faq_order','asc')->jsonPaginate(10);
        $data = [
            'testimonial_data' => $testimonial_data,
            'faq_data' => $faq_data,
        ];
        
        if ($request->is('api')) {
           return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        }

        return view('user.home')->with($data); 
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function privacy()
    { 
        return view('user.privacy-notice');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function termsCondition()
    { 
        return view('user.terms-condition');
    }
    
    public function shoFaq()
    {
        $faq_data = Faq::where('status', 1)->orderBy('faq_order','asc')->get();
        if(starts_with(request()->path(), 'api')){
            return apiResponse("success","",$faq_data);
        }
        return view('user.faq', compact('faq_data'));
    }
}
