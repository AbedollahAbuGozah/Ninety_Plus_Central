<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeBranchStatusRequest;
use App\Http\Requests\BranchRequest;
use App\Http\Resources\BranchResource;
use App\Models\Branch;
use App\Models\Module;
use App\services\BranchService;

class BranchController extends BaseController
{
    public function __construct(protected BranchService $branchService)
    {

    }

    public function index(BranchRequest $request, Module $module)
    {
        $branchs = Branch::query();
        return $this->success(BranchResource::collection($branchs, $request->boolean('paginate'), $request->get('page_size')), trans('messages.success.index'), 200);
    }

    public function store(BranchRequest $request, Module $module)
    {
        $validatedData = $request->safe()->all();
        $branch = $this->branchService->create($validatedData, new Branch(), []);
        return $this->success(BranchResource::make($branch), trans('messages.success.store'), 200);
    }

    public function show(BranchRequest $request, Branch $branch)
    {
        $branch = $this->branchService->get($branch, []);
        return $this->success(BranchResource::make($branch), trans('messages.success.index'), 200);
    }

    public function update(BranchRequest $request, Branch $branch)
    {
        $validatedData = $request->safe()->all();
        $branch = $this->branchService->update($validatedData, $branch, []);
        return $this->success(BranchResource::make($branch), trans('messages.success.update'), 200);
    }

    public function destroy(BranchRequest $request, Branch $branch)
    {
        $branch->delete();
        return $this->success([], trans('messages.success.delete'), 200);
    }

}
