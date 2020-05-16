<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <body>
        <div style="margin:auto; background-color:#1d1d1d; width:620px;border:#1d1d1d 10px solid; border-top:0 none;">
            <div style="background-color:#1d1d1d;">
                <div style='display:block;text-align:center;padding: 20px 0 15px 0;'>
                    <img border='0' src='{{ $message->embed(public_path(). '/images/j1app_logo.png') }}' />
                </div>
            </div>
            <div style="background:#FFFFFF; padding:15px; font-family:Arial, Helvetica, sans-serif; font-size:14px;"> 
                <div style="line-height:20px; margin-top: 20px;" id="email_body">{!! $message_text !!}</div>
                <br><br>
                <div style="text-align:left;line-height:20px;">Regards,</div>   
                <div style="text-align:right; font-style:italic; font-size:12px;line-height:20px;">{{ config("app.name") }}</div>
            </div>
        </div>
    </body>
</html>