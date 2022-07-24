<?php

namespace App\Http\Resources;

use App\JsonApi\Traits\JsonApiResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    use JsonApiResource;

    public function toJsonApi(): array
    {
        return [
            'title' => $this->resource->title,
            'description' => $this->resource->description,
        ];
    }

    public function getRelationshipLinks(): array
    {
        return ['manager'];
    }

    public function getIncludes(): array
    {
        return [
            ManagerResource::make($this->whenLoaded('manager')),
        ];
    }
}
