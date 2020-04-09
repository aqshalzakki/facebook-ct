<?php

namespace App\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->json([
            'errors' => [
                'status' => 404,
                'title' => 'User not Found!',
                'detail' => "Unable to locate the user with the given id of $request->id.",
            ]
        ], 404);
    }
}
