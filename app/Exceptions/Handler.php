<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Responses\JsonApiValidationErrorResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Throwable;


class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];


    public function register()
    {
        $this->renderable(function (NotFoundHttpException $e) {
            throw new JsonApi\NotFoundHttpException;
        });

        $this->renderable(function (BadRequestHttpException $e) {
            throw new JsonApi\BadRequestHttpException($e->getMessage());
        });

        $this->renderable(function (AuthenticationException $e) {
            throw new JsonApi\AuthenticationException;
        });
    }

    protected function invalidJson($request, ValidationException $exception): JsonResponse
    {
        if (! $request->routeIs('api.v1.login')) {
            return new JsonApiValidationErrorResponse($exception);
        }

        return parent::invalidJson($request, $exception);
    }
}
