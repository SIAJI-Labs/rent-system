<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

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

        // Spatie
        $this->renderable(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
            return response()->view('content.adm.exception', [
                'error_type' => 'spatie',
                'exception' => 'Maaf, anda tidak memiliki ijin untuk mengakses data terkait!'
            ]);
        });
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        $domain = $request->getHost();
        $url = preg_replace("(^https?://)", "", url()->current());
        $redirect = $exception->redirectTo() ?? route('login');
        if($request->expectsJson()){
            return response()->json(['message' => $exception->getMessage()], 401);
        }

        \Log::info("Check on Unauthenticated Function ~ App\Exceptions\Handler@unauthenticated", [
            'domain' => $url,
            'guard' => $exception->guards()
        ]);
        if(!empty($exception->guards())){
            foreach($exception->guards() as $guard){
                switch($guard){
                    case 'admin':
                        $redirect = route('adm.login');
                        break;
                }
            }
        }
        
        return redirect()->guest($redirect);
    }
}
