<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use App\Models\Faq;
use Validator;
use Auth;
use Response;
use Illuminate\Support\Facades\Storage;
use App\Traits\ImageTrait;

class FaqController extends Controller {

    use ImageTrait;

    /**
     * The authenticated admin.protected.
     *
     *  
     */
    protected $admin;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() { 
        $this->faq = new Faq;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show() {
        
       $faq = $this->faq->getFaqs()->all();

        if (!empty($faq)) {
            $data = [
                'faqs' => $faq
            ];
        } else {
            $data = ["error", "No data found"];
        }
        if(starts_with(request()->path(), 'api')){
            return apiResponse("success","",$data);
        }
        return view('admin.faq')->with($data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $faqs = (object) [
                    'id' => "",
                    'question' => "",
                    'answer' => "",
                    'status' => "",
        ];

        $data = [
            'faqs' => $faqs,
        ];
        return view('admin.faqadd')->with($data);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $rules = [
            'question' => 'required|unique:faq_master,question',
            'answer' => 'required',
        ];
        
        $validationErrorMessages = [
            'question.required' => 'FAQ question is required.',
            'question.unique' => 'FAQ question has already been taken.',
            'answer.required' => 'FAQ answer is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            if ($request->is('api/*')) { 
                return apiResponse("error",null,$validator->messages()->toArray(),401); 
            }
            return redirect(route('faq.add.form'))->with('errors', $validator->messages())->withInput();
        }
         
        $this->faq->question = $request->question;
        $this->faq->answer = $request->answer;
        $this->faq->status = $request->status;
        
        $this->faq->save();
        if ($request->is('api/*')){
            return apiResponse('success', "Your faq has been added successfully.");
        }
        return redirect(route('faq.list'))->with('success', "Your faq has been added successfully.");
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        
        $id = decrypt($id);
        
        $faq= $this->faq->getFaqs($id)->first();

        if(!empty($faq))
        {
            $data = [
                'faqs' => $faq,
                'id' => $id,
            ];
            return view('admin.faqadd')->with($data);
        }
        else {
            return redirect(route('faq.list'))->with("error", "No data found");
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        
        $faq_data = $this->faq::where('id', $id)->first();
        
        $rules = [
            'question' => "required|unique:faq_master,question,{$id},id",
            'answer' => 'required',
        ];
        
        $validationErrorMessages = [
            'question.required' => 'FAQ question is required.',
            'question.unique' => 'FAQ question has already been taken.',
            'answer.required' => 'FAQ answer is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

       if ( $validator->fails() ) { 
            if ($request->is('api/*')) { 
                return apiResponse("error",null,$validator->messages()->toArray(),401); 
            }else{
                return $validator->validate();
            }
        }
        
        $faq_data->question = $request->question;
        $faq_data->answer = $request->answer;
        $faq_data->status = $request->status;
        $faq_data->faq_order = $request->faq_order;
        $faq_data->save();
        if ($request->is('api/*')){
            return apiResponse('success', "FAQ Updated Successfully.");
        }
        return redirect(route('faq.list'))->with('success', "FAQ Updated Successfully.");
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  text  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        starts_with(request()->path(), 'api') ? $id = $id :$id = decrypt($id);
        if (Faq::deleteByFaqId($id)) {
            if(starts_with(request()->path(), 'api')){
                return apiResponse('success', "Faq Deleted Successfully");
            }
            return redirect(route('faq.list'))->with("success", "Faq Deleted Successfully");
        } else {
            if(starts_with(request()->path(), 'api')){
                return apiResponse("error", "Failed to delete faq");
            }
            return redirect(route('faq.list'))->with("error", "Failed to delete faq");
        }
    }
    
    public function setOrder(Request $request){
        foreach ($request->orderlist as $order){
            $id = preg_split ("/\,/", $order);
            $order = $id[1];
            $faq = Faq::where('id',$id[0])->first();
            $faq->faq_order = $order;
            $faq->save();
        }
        if ($request->is('api/*')){
            return apiResponse('success', "Faq Order Set Successfully!");
        }
        return "true";
    }
    
}
