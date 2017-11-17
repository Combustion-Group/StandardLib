<?php

namespace Combustion\StandardLib\Services\Assets\Support;

use Exception;
use Psr\Log\LoggerInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;
use Combustion\StandardLib\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Combustion\StandardLib\Exceptions\RecordNotFoundException;
use Combustion\StandardLib\Services\ACL\Exceptions\AclAccessDeniedException;

class ExceptionHandler extends Handler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
//        \Illuminate\Auth\AuthenticationException::class,
//        \Illuminate\Auth\Access\AuthorizationException::class,
//        \Symfony\Component\HttpKernel\Exception\HttpException::class,
//        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
//        \Illuminate\Session\TokenMismatchException::class,
//        \Illuminate\Validation\ValidationException::class,
    ];

    protected $exceptionResponses = [
        TokenExpiredException::class => ['message' => 'token_expired', 'code' => 401],
        TokenInvalidException::class => ['message' => 'token_invalid'],
        JWTException::class => ['message' => 'token_absent'],
        AclAccessDeniedException::class => ['message' => 'You are not authorized to perform that action.', 'code' => 403],
        ModelNotFoundException::class => ['message' => 'Resource not found', 'code' => 404],
        RecordNotFoundException::class => ['message' => 'Resource not found', 'code' => 404]
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $e)
    {
        if ($this->shouldntReport($e)) {
            return;
        }

        try {
            $logger = $this->container->make(LoggerInterface::class);
        } catch (Exception $ex) {
            throw $e; // throw the original exception
        }

        $logger->error($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if (\Config::get('app.debug') || Input::get('raw')) {
            // Log exception
            $this->report($e);

            return parent::render($request, $e);
        }

        $ec = get_class($e);

        if (array_key_exists($ec, $this->exceptionResponses)) {
            $data = $this->exceptionResponses[$ec];
            $code = isset($data['code']) ? $data['code'] : null;

            if (!$code) {
                if (method_exists($e, 'getStatusCode')) {
                    $code = $e->getStatusCode();
                } else {
                    $code = 400;
                }
            }

            return Controller::respond('', Controller::ERROR, $data['message'], $code);
        }

        // We only want to send the actual exception message if debug mode is enabled
        $message = Controller::getExceptionMessage($e);
        $code = $e instanceof HttpException ? $e->getStatusCode() : 500;
        // Send standard error message
        return Controller::respond('', Controller::ERROR, $message, $code);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Auth\AuthenticationException $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
