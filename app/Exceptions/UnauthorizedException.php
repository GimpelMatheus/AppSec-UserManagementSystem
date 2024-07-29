<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class UnauthorizedException extends Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        // Optionally log the exception or send a notification
        Log::error('Unauthorized access attempt.', [
            'exception' => $this->getMessage(),
            'trace' => $this->getTraceAsString(),
        ]);
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        if ($request->expectsJson()) {
            // Return a JSON response for API requests
            return response()->json([
                'message' => 'Unauthorized access.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Return a view for web requests
        return response()->view('errors.unauthorized', [], Response::HTTP_UNAUTHORIZED);
    }
}
