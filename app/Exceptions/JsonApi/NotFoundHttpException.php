<?php

namespace App\Exceptions\JsonApi;

use Exception;

class NotFoundHttpException extends Exception
{
    public function render($request)
    {
        $id = $request->input('data.id');
        $type = $request->input('data.type');

        return response()->json([
            'errors' => [
                [
                    'title' => 'Not Found',
                    'detail' => "Not records found with the id '{$id}' in '{$type}' resource",
                    'status' => '404',
                ],
            ],
        ], 404);
    }
}
