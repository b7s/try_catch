<?php

if(!function_exists('try_catch'))
{
    /**
     * Executes a callable and returns the result or error in a structured object.
     *
     * @param  callable  $callable
     * @param  bool  $throwError  It throws an error and will not return any value
     * @param  bool  $logErrors  Write the error to the system log
     * @param  Closure|null  $callbleOnError  Callable to execute on error
     *
     * @return array{data: mixed, error: ?Throwable} Associative array containing 'data' with the result of the operation or null (failure), and 'error' with the caught exception or null (success)
     * @throws Throwable
     */
    function try_catch(callable|Closure $callable, bool $throwError = false, bool $logErrors = true, ?Closure $callbleOnError = null): object
    {
        $result = new class {
            public $data = null;
            public $error = null;
        };

        try {
            $result->data = $callable();
            return $result;
        } catch (Throwable $e) {
            if ($logErrors) {
                error_log($e);
            }

            if (is_callable($callbleOnError)) {
                try {
                    $callbleOnError();
                } catch(Throwable $ce) {}
            }

            if($throwError)
                throw $e;

            $result->error = $e;
            return $result;
        }
    }
}
