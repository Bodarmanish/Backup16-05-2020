<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Response;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\ApplicationStatusController;
use Auth;
use DB;
use App\Models\NotificationLog AS NL;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class NotificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $NL;
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->NL = new NL;
    }
       
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $this->NL->setRead($user->id,'user');
        $notication_log = new NL;

        $notification = $notication_log->getUserNotificationList(null); 

        $request->ns = 1;
        $this->hasNotificationStatus($request);
        $notification_data = [];
        if(!empty($notification)){
        /*Start Pagination */
            $currentPage = Paginator::resolveCurrentPage();
            $col = collect($notification);
            $perPage = 5;
            $currentPageItems = $col->slice(($currentPage - 1) * $perPage, $perPage)->all();
            $notification_data = new Paginator($currentPageItems, count($col), $perPage);
            $notification_data->setPath($request->url());
            $notification_data->appends($request->all());
        /*End Pagination*/
        }
        
        return view('user.notifications')->with('data', compact('notification_data'));
    }
    
    public function viewNotification(Request $request, $log_id){
        
        if(!empty($log_id))
        {
            $notification_key = "";
            $un_list = json_decode(json_encode($request->user_notification_list),true);
            if(!empty($un_list))
            {
                $log_id = secure_id($log_id,"decrypt");
            
                /* Update Notification Id to Read */
                NL::setRead($log_id,'log');

                $un_log = $un_list[array_search($log_id, array_column($un_list,'log_id'))]; 
                $notification_key = $un_log['notification_key'];
            }
            
            switch($notification_key){
                case "application_status":
                    $timeline_key = $un_log['notification_type_data'];
                    $request->session()->put('direct_order_key',$timeline_key);
                    $notification_redirect_url = "application-status";
                break;

                case "messages":
                case "connection":
                case "forums":
                case "tagged":
                case "support_system":
                case "new_itn_features":
                case "invitation_to_participate":

                default:
                    $notification_redirect_url = "notifications";
                break;
            }

            return redirect(route($notification_redirect_url));
        }
    }  
    
    public function hasNotificationStatus(Request $request){
        $response = array();
        
        $post = $request->all();
        
        if((!empty($post['ns']) && $post['ns'] == 1) || (!empty($request->ns) && $request->ns == 1))
        {
            $user = Auth::user();
            $user->has_notification = 0;
            $user->save();
            
            $response = [
                'type' => "success",
                'message' => "Has j1 notification status updated"
            ];
        }
        else{
            $response = [
                'type' => "error",
                'message' => "Failed to updated has j1 notification status"
            ];
        }
        
        return Response::json($response);
            
    }
}
