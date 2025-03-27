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
     * @return array
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
