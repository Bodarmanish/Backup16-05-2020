<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body style="background:#FFFFFF; font-family:Arial, Helvetica, sans-serif; font-size:14px;">
        <div style="margin: 0 auto; max-width:900px; width: 100%; ">
            <div style="border:#b0413e 10px solid; padding: 20px;">
                @if(@$exception_message)
                    <div>
                        <p>{!! @$exception_message !!}</p>
                    </div>
                @endif
            </div>
        </div>
    </body>
</html>
