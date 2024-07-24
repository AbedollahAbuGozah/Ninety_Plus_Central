<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpFoundation\Exception\JsonException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];
    static $except = [
//        'Illuminate\Validation\ValidationException',
//        'Illuminate\Auth\AuthenticationException',
    ];

    public function register(): void
    {
        $this->renderable(function (Throwable $e) {
            DB::rollBack();
            if (in_array(get_class($e), self::$except) || config('app.debug')) {
                return;
            }
            return response()->json($this->getCustomMessage($e), $this->getStatusCode($e));
        });
    }

    protected function getCustomMessage(Throwable $e)
    {
        if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
            return ['message' => 'The resource is not found.'];
        } elseif ($e instanceof ValidationException) {
            return ['message' => 'Validation failed.'];
        } elseif ($e instanceof AuthenticationException) {
            return ['message' => 'Authentication failed.'];
        } elseif ($e instanceof AuthorizationException) {
            return ['message' => 'Authorization failed.'];
        } elseif ($e instanceof QueryException) {
            return ['message' => 'Database query error.'];
        } elseif ($e instanceof ThrottleRequestsException) {
            return ['message' => 'Too many attempts, please try again later.'];
        } elseif ($e instanceof TokenMismatchException) {
            return ['message' => 'CSRF token mismatch.'];
        } elseif ($e instanceof MethodNotAllowedHttpException) {
            return ['message' => 'HTTP method not allowed.'];
        } elseif ($e instanceof JsonException) {
            return ['message' => 'JSON processing error.'];
        } else {
            return ['message' => $e->getMessage()];
        }
    }

    protected function getStatusCode(Throwable $e)
    {
        if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
            return 404;
        } elseif ($e instanceof ValidationException) {
            return 422;
        } elseif ($e instanceof AuthenticationException || $e instanceof QueryException || $e instanceof JsonException) {
            return 400;
        } elseif ($e instanceof AuthorizationException) {
            return 403;
        } elseif ($e instanceof ThrottleRequestsException) {
            return 429;
        } elseif ($e instanceof TokenMismatchException) {
            return 419;
        } elseif ($e instanceof MethodNotAllowedHttpException) {
            return 405;
        } else {
            return 422;
        }
    }
}
