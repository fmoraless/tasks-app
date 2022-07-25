<?php

namespace App\JsonApi;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

//use App\Exceptions\JsonApi\BadRequestHttpException;

class JsonApiQueryBuilder
{
    public function allowedFilters(): Closure
    {
    return function($allowedFilters) {
        /** @var Builder $this */
        foreach (request('filter', []) as $filter => $value) {

            if (! in_array($filter, $allowedFilters)) {
                throw new BadRequestHttpException("The filter '{$filter}' is not allowed in the '{$this->getResourceType()}' resource.");
            }

            $this->hasNamedScope($filter)
                ? $this->{$filter}($value)
                : $this->where($filter, 'LIKE', '%'.$value.'%');
        }
        return $this;
    };
    }

    public function allowedIncludes():Closure
    {
        return function ($allowedIncludes) {
            /** @var Builder $this */
            if (request()->isNotFilled('include')) {
                return $this;
            }
            $includes = explode(',', request()->input('include'));
            foreach ($includes as $include) {

                if (! in_array($include, $allowedIncludes)) {
                    throw new BadRequestHttpException("The included relationship '{$include}' is not allowed in the '{$this->getResourceType()}' resource.");
                }
                $this->with($include);
            }


            return $this;
        };
    }

    public function jsonPaginate(): Closure
    {
        return function() {
            /** @var Builder $this */
            return $this->paginate(
                $perPage = request('page.size', 10),
                $columns = ['*'],
                $pageName = 'page[number]',
                $page = request('page.number', 1)
            )->appends(request()->only('filter', 'page.size'));
        };
    }

    public function getResourceType(): Closure
    {
        return function() {
            /** @var Builder $this */

            if (property_exists($this->model, 'resourceType')) {
                return $this->model->resourceType;
            }

            return $this->model->getTable();

        };
    }
}
