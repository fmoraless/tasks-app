<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class JsonApiValidationErrorResponse extends JsonResponse
{

    public function __construct(ValidationException $exception, $status = 422)
    {


        /* en vez de foreach, se puede utilizar collections */
        $data = $this->formatJsonApiErrors($exception);
        $headers = [
            'content-type' => 'application/vnd.api+json'
        ];

        parent::__construct($data, $status, $headers);
    }

    /**
     * @param ValidationException $exception
     * @param string $title
     * @return array
     */
    public function formatJsonApiErrors(ValidationException $exception): array
    {
        $title = $exception->getMessage();
        return [
            'errors' => collect($exception->errors())
                ->map(function ($messages, $field) use ($title) {
                    return [
                        'title' => $title,
                        'detail' => $messages[0],
                        'source' => [
                            'pointer' => "/" . str_replace('.', '/', $field)
                        ]
                    ];
                })->values()
        ];
    }
}
