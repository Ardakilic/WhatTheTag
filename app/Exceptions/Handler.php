<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        TokenMismatchException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {

        //Whoops integrated
        //Thanks to : http://ryanwinchester.ca/post/whoops-in-laravel-5-1
        if (config('app.debug')) {
            return $this->renderExceptionWithWhoops($request, $e);
        }
        
        //Laravel automatically finds the 404 view, these lines are not needed either
        /* else {
            return response()->view('errors.404', [], 404);
        }*/
        
        //I want everything to be handled with whoops. So I'm commenting out these
        /*
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }
        */

        return parent::render($request, $e);
    }
    
    
    /**
     * Render an exception using Whoops.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return Response
     */
    protected function renderExceptionWithWhoops($request, Exception $e)
    {
        $whoops = new \Whoops\Run;
    
        if ($request->ajax()) {
            $whoops->pushHandler(new \Whoops\Handler\JsonResponseHandler());
        } else {
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
        }
    
        return new \Illuminate\Http\Response(
            $whoops->handleException($e),
            $e->getStatusCode(),
            $e->getHeaders()
        );
    }

}