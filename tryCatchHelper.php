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
    function try_catch(callable $callable, bool $throwError = false, bool $logErrors = true): array
    {
        try {
            $data = $callable();
            return ['data' => $data, 'error' => null];
        } catch (Throwable $e) {
            if ($logErrors) {
                Log::error($e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }

            if($throwError) {
                throw $e;
            }

            return ['data' => null, 'error' => $e];
        }
    }
}
