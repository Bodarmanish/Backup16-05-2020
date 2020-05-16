<?php

namespace App\Traits;

use App\Logic\Activation\ActivationRepository;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Mail;
use DB;

trait MailerTrait
{ 
    public static function sendUIEmailNotification($recipient, $subject, $data, $template = null, $from = null, $attachments = null)
    {
        if(empty($template))
            $template = "emailTemplate";
        
        $no_reply_email = config("common.no_reply_email");
        $from_name = config("common.from_name");
        
        $temp_from = array();
        if(isset($from->email) && !empty($from->email)) {
            $from->name = (isset($from->name))?$from->name:"";
        }
        else {
            $temp_from['email'] = $no_reply_email;
            $temp_from['name'] = $from_name;
            
            $from = (object) $temp_from;
        }
        
        $sent = Mail::send("email.{$template}", $data, 
            function($message) use ($recipient, $subject, $from, $attachments) {
            
                if(isset($recipient->toEmail) && !empty($recipient->toEmail))
                {
                    $recipient->toName = (isset($recipient->toName))?$recipient->toName:"";
                    $message->to($recipient->toEmail,$recipient->toName);
                    
                    if(isset($recipient->ccEmail) && !empty($recipient->ccEmail))
                    {
                        $recipient->ccName = (isset($recipient->ccName))?$recipient->ccName:"";
                        $message->cc($recipient->ccEmail, $recipient->ccName);
                    }
                    
                    if(isset($recipient->bccEmail) && !empty($recipient->bccEmail))
                    {
                        $recipient->bccName = (isset($recipient->bccName))?$recipient->bccName:"";
                        $message->bcc($recipient->bccEmail, $recipient->bccName);
                    }
                    
                    if(isset($from->email) && !empty($from->email)) {
                        $from->name = (isset($from->name))?$from->name:"";
                        $message->from($from->email, $from->name);
                    }
                    
                    $message->subject($subject);
                    
                    if(!empty($attachments))
                    {
                        foreach($attachments as $attachment){

                            if(!empty($attachment['filepath']))
                            {
                                $attach_file = $attachment['filepath'];
                                
                                $attach_display = (!empty($attachment['filename']))?$attachment['filename'] : basename($attach_file);
                                $attach_mime = (!empty($attachment['mime']))?$attachment['mime'] : mime_content_type($attach_file);
                                
                                $attach_display_info = pathinfo($attach_display);
                                
                                if(!empty($attach_display) && empty($attach_display_info['extension']))
                                {
                                    $attach_file_info = pathinfo($attach_file);
                                    $attach_display = $attach_display.".".$attach_file_info['extension'];
                                }
                                
                                $message->attach($attach_file, ['as'=>$attach_display, 'mime'=>$attach_mime]);
                            }
                        }
                    }
                }
        });
        
        return $sent;
    }
    
    public function sendQueryBreakNotification($data)
    {  
        $recipient = new \stdClass();
        $recipient->toEmail = config('common.query_break_email.to');
        $cc = config('common.query_break_email.cc');
        
        $cc_email = array();
        $cc_name = array();
        foreach($cc as $key => $val){
             $cc_email[] = $val;
             $cc_name[] = $key;
        }
        $recipient->ccEmail = $cc_email;
        $recipient->ccName = $cc_name;
        
        $template = "errors.exception";
        
        $no_reply_email = config("common.no_reply_email");
        $from_name = config("common.from_name");
        
        $temp_from['email'] = $no_reply_email;
        $temp_from['name'] = $from_name;

        $from = (object) $temp_from;
        
        $subject = "ITN DB SQL QUERY IS BREAK DOWN AS ON ".date('m-d-Y h:i A');
        
        /** Declare html body here no need to pass view in mail::send method **
        $html_body = view($template)->with(['data' => $data])->render();
        /****/
        
        $sent = Mail::send($template, $data, 
            function($message) use ($recipient,$subject,$from) {
            
                if(isset($recipient->toEmail) && !empty($recipient->toEmail))
                {
                    $recipient->toName = (isset($recipient->toName))?$recipient->toName:"";
                    $message->to($recipient->toEmail,$recipient->toName);
                    
                    if(isset($recipient->ccEmail) && !empty($recipient->ccEmail))
                    {
                        $recipient->ccName = (isset($recipient->ccName))?$recipient->ccName:"";
                        $message->cc($recipient->ccEmail, $recipient->ccName);
                    }
                    
                    if(isset($recipient->bccEmail) && !empty($recipient->bccEmail))
                    {
                        $recipient->bccName = (isset($recipient->bccName))?$recipient->bccName:"";
                        $message->bcc($recipient->bccEmail, $recipient->bccName);
                    }
                    
                    if(isset($from->email) && !empty($from->email)) {
                        $from->name = (isset($from->name))?$from->name:"";
                        $message->from($from->email, $from->name);
                    }
                    
                    $message->subject($subject);
                    
                    /** Options for add direct html body here **
                    $message->setBody($html_body, 'text/html');
                    /****/
                }
        });
        
        return $sent;
    }
    
    public function sendErrorNotification($data)
    { 
        $recipient = new \stdClass();
        $recipient->toEmail = config('common.query_break_email.to');
        $cc = config('common.query_break_email.cc');
        
        $cc_email = array();
        $cc_name = array();
        foreach($cc as $key => $val){
             $cc_email[] = $val;
             $cc_name[] = $key;
        }
        $recipient->ccEmail = $cc_email;
        $recipient->ccName = $cc_name;
        
        $template = "errors.exception";
        
        $no_reply_email = config("common.no_reply_email");
        $from_name = config("common.from_name");
        
        $temp_from['email'] = $no_reply_email;
        $temp_from['name'] = $from_name;

        $from = (object) $temp_from;
        
        $subject = "Error Exception AS ON ".date('m-d-Y h:i A');
        
        /** Declare html body here no need to pass view in mail::send method **
        $html_body = view($template)->with(['data' => $data])->render();
        /****/
        
        $sent = Mail::send($template, $data, 
            function($message) use ($recipient,$subject,$from) {
            
                if(isset($recipient->toEmail) && !empty($recipient->toEmail))
                {
                    $recipient->toName = (isset($recipient->toName))?$recipient->toName:"";
                    $message->to($recipient->toEmail,$recipient->toName);
                    
                    if(isset($recipient->ccEmail) && !empty($recipient->ccEmail))
                    {
                        $recipient->ccName = (isset($recipient->ccName))?$recipient->ccName:"";
                        $message->cc($recipient->ccEmail, $recipient->ccName);
                    }
                    
                    if(isset($recipient->bccEmail) && !empty($recipient->bccEmail))
                    {
                        $recipient->bccName = (isset($recipient->bccName))?$recipient->bccName:"";
                        $message->bcc($recipient->bccEmail, $recipient->bccName);
                    }
                    
                    if(isset($from->email) && !empty($from->email)) {
                        $from->name = (isset($from->name))?$from->name:"";
                        $message->from($from->email, $from->name);
                    }
                    
                    $message->subject($subject);
                    
                    /** Options for add direct html body here **
                    $message->setBody($html_body, 'text/html');
                    /****/
                }
        });
        
        return $sent;
    }
    
}