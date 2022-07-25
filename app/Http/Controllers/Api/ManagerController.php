<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ManagerResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class ManagerController extends Controller
{
    public function show($manager): JsonResource
    {
        $manager = User::findOrFail($manager);
        return ManagerResource::make($manager);
    }

    public function index(): AnonymousResourceCollection
    {
        $manager = User::jsonPaginate();

        return ManagerResource::collection($manager);
    }
}
