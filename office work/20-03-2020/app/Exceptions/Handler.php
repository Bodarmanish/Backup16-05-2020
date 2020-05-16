<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Illuminate\Auth\AuthenticationException;
use Auth;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException)
        {
            return apiResponse("error","Not found.",null,401);
        }
        else if($exception instanceof UnauthorizedException)
        {
            return $this->unauthorized($request, $exception);
        }
        
        return parent::render($request, $exception);
    }
    
    protected function unauthenticated($request, AuthenticationException $exception)
    {  
        if ($request->is('api/*')) {
            return apiResponse("error","Unauthenticated.",null,401);
        }
//        if ($request->is('admin') || $request->is('admin/*')) {
//            return redirect()->guest('/login/admin');
//        }
        return redirect()->guest(route('login'));
    }
    
    protected function unauthorized($request, UnauthorizedException $exception)
    {
        $data = [
            'error_code' => 403,
            'error_message' => "Unauthorized Access",
            'redirect_url' => $exception->redirectTo()
        ];
        
        if ($request->is('api/*')) {
            return apiResponse("error", "Unauthorized Access", "", 403);
        }
        else{
            return response()->view('errors.error',$data);
        }
    }
     
}
