<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof TokenInvalidException) {
            return response()->json(['error' => 'Token is invalid'], 401);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return response()->json(['error' => 'MethodNotAllowed'], 405);
        }
        if ($e instanceof NotFoundHttpException) {

            return not_found_response();
        }
        if ($e instanceof ModelNotFoundException) {
            return not_found_response();
        }

        if ($e instanceof AuthorizationException) {
            return forbidden_response();
        }
        return parent::render($request, $e);
        //  return internal_server_error_response();
    }
}
