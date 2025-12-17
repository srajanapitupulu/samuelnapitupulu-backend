<?php

namespace App\Http\Controllers\Api;

use App\Services\HierarchyService;
use Illuminate\Http\Request;

class HierarchyController extends ApiController
{
    public function show(Request $request, HierarchyService $service)
    {
        return $this->success(
            $service->forUser($request->user()),
            'Hierarchy'
        );
    }
}
