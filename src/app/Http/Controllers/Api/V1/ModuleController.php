<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ModuleRequest;
use App\Http\Resources\ModuleResource;
use App\Models\Country;
use App\Models\Module;
use App\services\ModuleService;
use App\Traits\HttpResponse;

class ModuleController extends BaseController
{
    use HttpResponse;

    public function __construct(protected ModuleService $moduleService)
    {

    }

    public function index(ModuleRequest $request, Country $country)
    {
        $modules = $country->modules()->with(['branch', 'country'])->paginate($request->get('per_page') ?? 10);
        return $this->success(ModuleResource::collection($modules), trans('messages.success.index'), 200);
    }

    public function store(ModuleRequest $request, Country $country)
    {
        $validatedData = $request->safe()->all();
        $module = $this->moduleService->create($validatedData, new Module(), ['country', 'branch']);
        return $this->success(ModuleResource::make($module), trans('messages.success.store'), 201);
    }

    public function show(ModuleRequest $request, Module $module)
    {
        $module = $this->moduleService->get($module, ['country', 'branch', 'chapters']);
        return $this->success(ModuleResource::make($module), 'messages.success.show', 200);
    }

    public function update(ModuleRequest $request, Module $module)
    {
        $validatedData = $request->safe()->all();
        $this->moduleService->update($validatedData, $module, ['country', 'branch']);
        return $this->success($module, 'messages.success.update', 200);
    }

    public function destroy(ModuleRequest $request, Module $module)
    {
        $this->moduleService->delete($module);
        return $this->success([], 'messages.success.delete', 200);
    }
}
