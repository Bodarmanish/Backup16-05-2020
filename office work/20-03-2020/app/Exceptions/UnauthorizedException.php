<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedException extends Exception
{
    /**
     * The path the user should be redirected to.
     *
     * @var string
     */
    protected $redirectTo;
    
    /**
     * Create a new Unauthorized exception.
     *
     * @param  string  $message
     * @return void
     */
    public function __construct($message = "Unauthorized Access", $redirectTo = null)
    {
        parent::__construct($message);
        
        $this->redirectTo = $redirectTo;
    }
    
    /**
     * Get the path the user should be redirected to.
     *
     * @return string
     */
    public function redirectTo()
    {
        return $this->redirectTo;
    }
}
