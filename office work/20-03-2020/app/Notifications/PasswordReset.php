<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\EmailNotification as EN;

class PasswordReset extends Notification
{
    use Queueable;

    /**
    * The password reset token.
    *
    * @var string
    */
    public $token; 
    
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {   
        $user_id = trim($notifiable->user_id); 
        $candidate_email = $notifiable->email; 
        $first_name = trim($notifiable->first_name);
        $last_name = trim($notifiable->last_name); 
        $candidate_name = trim($first_name." ".$last_name);
        $url = url(route('password.reset', ['token'=>$this->token], false));
        //$url = config('app.url')."password/reset/{$this->token}";
                 
        $mail_format = (array) EN::getMailTextByKey("reset_password");
        $subject      = $mail_format['subject'];
        $message_text = $mail_format['text'];
        $message_text = str_replace("{{first_name}}", $first_name, $message_text);
        $message_text = str_replace("{{last_name}}", $last_name, $message_text);
        $message_text = str_replace("{{email}}", $candidate_email, $message_text);
        $message_text = str_replace("{{company_name}}", config('app.name'), $message_text); 
        $message_text = str_replace("{{url}}", $url, $message_text);
        
        $data = ['message_text' => $message_text]; 
        
        /*return $this->sendUIEmailNotification((object) $receiver, $subject, $data);*/
        return (new MailMessage)
            ->subject($subject)
            ->view('email.emailTemplate',$data);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               