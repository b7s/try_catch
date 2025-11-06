<?php

if(!function_exists('tryIt'))
{
    /**
     * Executes a callable and returns the result or error in a structured object.
     *
     * @param  callable|Closure  $callable  Callable to execute
     * @param  bool  $throwError  Whether to throw the error
     * @param  bool  $logErrors  Whether to log the error
     * @param  Closure|null  $callbleOnError  Callable to execute on error
     *
     * @return object{data: mixed, error: ?Throwable, callbleOnErrorReturn: mixed|Throwable} 
     * Object containing:
     * - 'data' with the result of the operation or null (failure), 
     * - 'error' with the caught exception or null (success)
     * - 'callbleOnErrorReturn' with:
     *  - null when there is no prior error (default)
     *  - result<mixed> of the callbleOnError() if theres a previous error
     *  - Throwable if callbleOnError() fails
     *
     * @throws Throwable
     */
    function tryIt(callable|Closure $callable, bool $throwError = false, bool $logErrors = true, ?Closure $callbleOnError = null): object
    {
        $result = new class {
            public $data = null;
            public $error = null;
            public mixed $callbleOnErrorReturn = null;
        };

        try {
            $result->data = $callable();
            return $result;
        } catch (Throwable $e) {
            if (is_callable($callbleOnError)) {
                try {
                    $result->callbleOnErrorReturn = $callbleOnError();
                } catch (Throwable $ce) {
                    $result->callbleOnErrorReturn = $ce;
                }
            }

            if ($logErrors) {
                error_log($e);
            }

            if($throwError)
                throw $e;

            $result->error = $e;
            return $result;
        }
    }
}
