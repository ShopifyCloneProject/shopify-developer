<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Http\Controllers\Traits\ApiResponser;
use Throwable;
use Facades\App\ApiResponses\DomainResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use GuzzleHttp\Exception\ClientException;

class Handler extends ExceptionHandler
{
     use ApiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
       
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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        
        if(Request()->ajax())
        {
           $this->renderReportApi($exception);
        }
        else
        {
            if($exception->getMessage() == 'Unauthenticated.'){
                return redirect('/login');
            }

            if($exception instanceof ValidationException)
            {
                return redirect()->back()->with('error','Please enter valid credentials!!');
               // return $this->convertValidationExceptionToResponse($exception, $request);
            }

        }

    }

    public function render($request, Throwable $exception)
    {
       return parent::render($request, $exception);
    }

     /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function renderReportApi(Throwable $exception)
    {
        $params = Request()->params;
        $params = json_decode($params,true);
        $request = Request();
      
        if(isset($params['laraveldebug']) && $params['laraveldebug'])
        {
           dd($exception);
        }
        /* Return normal exception while request from web */
       

        /* Return Exception in JSON format while request from API */
        if($exception instanceof AuthenticationException)
        {
            return redirect('/login');
            return $this->unauthenticated($request, $exception);
        }

        if($exception instanceof ValidationException)
        {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        if($exception instanceof ModelNotFoundException)
        {
            $modalName = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse(__("constants.ERROR_STATUS"),404,"Does not exists any {$modalName} with the specified identificator");
        }

        if($exception instanceof AuthorizationException)
        {
            return $this->errorResponse(__("constants.ERROR_STATUS"),403,$exception->getMessage());
        }

        if($exception instanceof NotFoundHttpException)
        {
            return $this->errorResponse(__("constants.ERROR_STATUS"),404,"The specified URL can not be found");
        }

        if($exception instanceof MethodNotAllowedHttpException)
        {
            return $this->errorResponse(__("constants.ERROR_STATUS"),405,"The specified method for the request is invalid");
        }

        if($exception instanceof DecryptException)
        {
            return $this->errorResponse(__("constants.ERROR_STATUS"),500,"The specified value could not decrypt");
        }

        if($exception instanceof HttpException)
        {   
            $responseData = $this->errorResponse(__("constants.ERROR_STATUS"),$exception->getStatusCode(),$exception->getMessage());
            echo json_encode($responseData->original);
            exit;
        }

        if($exception instanceof ClientException)
        {
            return $this->errorResponse(__("constants.ERROR_STATUS"),300,$exception->getMessage());
        }

        if($exception instanceof QueryException)
        {
            $errorCode = $exception->errorInfo[1];
            $errorMsg  = $exception->errorInfo[2];
            if($errorCode == 1451)
            {
                return $this->errorResponse(__("constants.ERROR_STATUS"),409,"Can not remove this resource permanently. It is related with any other resource.");
            }
            else if($errorCode == 1062)
            {
                return $this->errorResponse(
                    __('constants.ERROR_STATUS'),
                    __('constants.errors.ALREADY_EXIST.code'),
                    __('constants.errors.ALREADY_EXIST.msg'),
                );
            }
            else
            {
                return $this->errorResponse(__("constants.ERROR_STATUS"),409," Unexpected Error in SQL. ".$errorMsg );
            }
        }

        if(config('app.debug'))
        {
           parent::render(\Request(), $exception);
        }
        return $this->errorResponse(__("constants.ERROR_STATUS"),500, "Unexpected Exception. Try later". $exception->getMessage());
        

    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
   /* public function register()
    {
        $this->reportable(function (Throwable $e) {
        });
    }*/
}
