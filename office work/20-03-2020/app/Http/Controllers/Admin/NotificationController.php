<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Illuminate\Support\Str;
use App\Models\NotificationType;
use App\Models\NotificationMessage;
use App\Models\EmailNotification;
use Validator;
use Auth;

class NotificationController extends Controller {

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
        $this->notification = new NotificationType;
        $this->notificationmsg = new NotificationMessage;
        $this->emailnotification = new EmailNotification;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showType() {

        $notification_types = $this->notification::all();
        
        if(!empty($notification_types))
        {
            $data = [
                'notification_types' => $notification_types
            ];
        }
        if(starts_with(request()->path(), 'api')){ 
            return apiResponse("success","",$data);
        }
        else
        {
            return view('admin.notification-type')->with($data);
        }
    }
    /**
     * Change the notification type status.
     *
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request) {
        $action = $request->get('action',"");
        $notification_id = $request->get('notification_id',"");
        $is_active = $request->get('is_active',"");
        
        if($action == "change_notification_status"){
            $notification = $this->notification::where('id', $notification_id)->first();
            $notification->status = $is_active;
            $notification->save();
            return apiResponse("success","Notification status updated successfully");
        }
        
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editJNT($id) {
        $id = decrypt($id);
        $notification = $this->notification::where('id', $id)->first();
        
        if(!empty($notification))
        {
            $data = [
                'notification' => $notification,
                'id' => $id,
            ];
            return view('admin.notification-type-edit')->with($data);
        }
        else {
            return redirect(route('notification.type.list'))->with("error", "No data found");
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateJNT(Request $request, $id) {
        
        $notification = $this->notification::where('id', $id)->first();
        $rules = [
            'notification_key'  =>  'required|unique:notification_types,notification_key,'.$notification->id,
            'notification_name' => 'required',
        ];
        $validationErrorMessages = [
            'notification_key.required' => 'Notification Key field is required.',
            'notification_key.unique' => 'Notification Key has already been taken.',  
            'notification_name.required' => 'Notification Name field is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

        if ( $validator->fails() ) { 
            if ($request->is('api/*')) { 
                return apiResponse("error",null,$validator->messages()->toArray(),401); 
            }
            else{
                return $validator->validate();
            }
        }
        $notification->notification_key = $request->notification_key;
        $notification->notification_name = $request->notification_name;
        $notification->visible_to_user = $request->visible_to_user;
        $notification->notification_mode = $request->notification_mode;
        $notification->save();
        if($request->is('api/*')){ 
            return apiResponse('success', "Notification type updated successfully.");
        }
        return redirect(route('notification.type.list'))->with('success', "Notification type updated successfully.");
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  text  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyJNT($id) {
        starts_with(request()->path(), 'api') ? $id = $id : $id = decrypt($id);
        if ($this->notification::deleteByNotificationId($id)) {
            if(starts_with(request()->path(), 'api')){ 
                return apiResponse("success", "Notification type deleted successfully");
            }
            return redirect(route('notification.type.list'))->with("success", "Notification type deleted successfully");
        } else {
            if(starts_with(request()->path(), 'api')){ 
                return apiResponse("error", "Failed to delete notification type");
            }
            return redirect(route('notification.type.list'))->with("error", "Failed to delete notification type");
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showMessage(Request $request) {

        $notification_types = $this->notification::all();
        
        $params = $request->except('_token');
        $notification_messages = $this->notificationmsg::filter($params)->get();
        
        if(!empty($notification_messages))
        {
            $data = [
                'notification_types' => $notification_types,
                'notification_messages' => $notification_messages
            ];
        }
        if ($request->is('api/*')) { 
            return apiResponse("success","",$data);
        }
        return view('admin.notification-message')->with($data);
    }
    /**
     * Change the notification message status.
     *
     * @return \Illuminate\Http\Response
     */
    public function changeMessageStatus(Request $request) {
        $action = $request->get('action',"");
        $notification_id = $request->get('notification_id',"");
        $is_active = $request->get('is_active',"");
        
        if($action == "change_notification_message_status"){
            $notification = $this->notificationmsg::where('id', $notification_id)->first();
            $notification->status = $is_active;
            $notification->save();
            return apiResponse("success","Notification status updated successfully.");
        }
        
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editJNM($id) {
        $id = decrypt($id);
        $notification = $this->notificationmsg::where('id', $id)->first();
        
        if(!empty($notification))
        {
            $data = [
                'notification' => $notification,
                'id' => $id,
            ];
            return view('admin.notification-message-edit')->with($data);
        }
        else {
            return redirect(route('notification.message.list'))->with("error", "No data found");
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateJNM(Request $request, $id) {
        
        $notification = $this->notificationmsg::where('id', $id)->first();
        
        $rules = [
            'notification_text'  =>  'required',
            'notification_msg' => 'required',
        ];
        $validationErrorMessages = [
            'notification_text.required' => 'Notification Text field is required.',
            'notification_msg.required' => 'Notification Message field is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

        if ( $validator->fails() ) {
            if ($request->is('api/*')) {
                return apiResponse("error",null,$validator->messages()->toArray(),401); 
            }
            else{
                return $validator->validate();
            }
        }
        $notification->notification_text = $request->notification_text;
        $notification->notification_message = $request->notification_msg;
        $notification->save();
        if ($request->is('api/*')) {
            return apiResponse('success', "Notification message updated successfully."); 
        }
        return redirect(route('notification.message.list'))->with('success', "Notification message updated successfully.");
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  text  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyJNM($id) {
        starts_with(request()->path(), 'api') ? $id = $id : $id = decrypt($id);
        if ($this->notificationmsg::deleteByNotificationId($id)) {
            if (starts_with(request()->path(), 'api')) {
                return apiResponse("success", "Notification message deleted successfully"); 
            }
            return redirect(route('notification.message.list'))->with("success", "Notification message deleted successfully");
        } else {
            if (starts_with(request()->path(), 'api')) {
                return apiResponse("error", "Failed to delete notification message");
            }
            return redirect(route('notification.message.list'))->with("error", "Failed to delete notification message");
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showEN(Request $request) {

        $params = $request->except('_token');
        $email_notification = $this->emailnotification::filter($params)->get();
        $data = [
                'email_notification' => $email_notification
            ];
        if($request->is('api/*')){
            return apiResponse("success","",$data);
        }
        return view('admin.email-notification')->with($data);
    }
    /**
     * Change the notification message status.
     *
     * @return \Illuminate\Http\Response
     */
    public function changeEmailStatus(Request $request) {
        $action = $request->get('action',"");
        $notification_id = $request->get('notification_id',"");
        $is_active = $request->get('is_active',"");
        
        if($action == "change_email_notification_status"){
            $notification = $this->emailnotification::where('id', $notification_id)->first();
            $notification->status = $is_active;
            $notification->save();
            return apiResponse("success","Email notification status updated successfully");
        }
        
    }
     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editEN($id) {
        $id = decrypt($id);
        $notification = $this->emailnotification::where('id', $id)->first();
        
        if(!empty($notification))
        {
            $data = [
                'notification' => $notification,
                'id' => $id,
            ];
            return view('admin.email-notification-edit')->with($data);
        }
        else {
            return redirect(route('email.notification.list'))->with("error", "No data found");
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateEN(Request $request, $id) {
        
        $notification = $this->emailnotification::where('id', $id)->first();
        
        $rules = [
            'subject'  =>  'required',
            'text' => 'required',
            'send_cc' => 'required',
        ];
        $validationErrorMessages = [
            'subject.required' => 'Subject field is required.',
            'text.required' => 'Text field is required.',
            'send_cc.required' => 'Send CC field is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

        if ( $validator->fails() ) {
            if ($request->is('api/*')) {
                return apiResponse("error",null,$validator->messages()->toArray(),401); 
            }
            else{
                return $validator->validate();
            }
        }
        $notification->subject = $request->subject;
        $notification->text = $request->text;
        $notification->send_cc = $request->send_cc;
        $notification->send_to = $request->send_to;
        $notification->save();
        if($request->is('api/*')){
            return apiResponse('success', "Email notification updated successfully.");
        }
        return redirect(route('email.notification.list'))->with('success', "Email notification updated successfully.");
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  text  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyEN($id) {
        starts_with(request()->path(), 'api') ? $id =$id : $id = decrypt($id);
        if ($this->emailnotification::deleteByEmailNotificationId($id)) {
            if (starts_with(request()->path(), 'api')) {
                return apiResponse("success", "Email notification deleted successfully"); 
            }
            return redirect(route('email.notification.list'))->with("success", "Email notification deleted successfully");
        } else {
            if (starts_with(request()->path(), 'api')) {
                return apiResponse("error", "Failed to delete email notification"); 
            }
            return redirect(route('email.notification.list'))->with("error", "Failed to delete email notification");
        }
    }
}
