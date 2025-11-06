<?php

use Illuminate\Support\Facades\Log;

if(!function_exists('try_catch'))
{
    /**
     * Executes a callable and returns the result or error in a structured array.
     *
     * @param callable $callable
     * @param bool $throwError
     * @param bool $logErrors
     * @return array{data: mixed, error: ?Throwable} Associative array containing 'data' with the result of the operation or null (failure), and 'error' with the caught exception or null (success)
     * @throws Throwable
     */
    function try_catch(callable|Closure $callable, bool $throwError = false, bool $logErrors = true, ?Closure $onErrorClosure = null): object
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
                Log::error($e->getMessage(), [
                    'file'  => $e->getFile(),
                    'line'  => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }

            throw_if($throwError, fn() => throw $e);

            if (is_callable($onErrorClosure)) {
                $onErrorClosure();
            }

            $result->error = $e;
            return $result;
        }
    }
}
