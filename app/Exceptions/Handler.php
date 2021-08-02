<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
		$this->renderable(function (NotFoundHttpException $e, $request) {
			if ($request->is('api/*')) {
				return response()->json(['error' => 'Not Found'], 404);
			}
			return response('The specified URL cannot be  found.', 404);
		});
    }
	
	//public function render($request, Throwable $exception)
	//{
		//if ($exception instanceof ModelNotFoundException){ // && $request->wantsJson()) {
			//echo 1; exit;
			//return response()->json(['message' => 'Not Found!'], 404);
		//}

		//return parent::render($request, $exception);
		// return response()->json($e, 500);
	//}
	
	/*
	if ($exception instanceof NotFoundHttpException) {
			print_r($request);exit;
			if ($request->is('api/*')) {
				return response()->json(['error' => 'Not Found'], 404);
			}
			return response()->view('404', [], 404);
		}
		return parent::render($request, $exception);*/
}
