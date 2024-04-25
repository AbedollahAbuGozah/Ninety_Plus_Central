<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreModuleRequest;
use App\Http\Requests\UpdateModuleRequest;
use App\Http\Resources\ModuleResource;
use App\Models\Country;
use App\Models\Module;
use App\services\ModuleService;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    use HttpResponse;

    public function __construct(protected ModuleService $moduleService)
    {

    }

    public function index(Request $request, Country $country)
    {
        $modules = $country->modules()->with(['branch', 'country'])->get();
        return $this->success(ModuleResource::collection($modules), trans('messages.success.index'), 200);
    }

    public function store(StoreModuleRequest $request, Country $country)
    {

        $validatedData = $request->safe()->all();
        $module = $this->moduleService->create($validatedData, new Module(), ['country', 'branch']);
        return $this->success(ModuleResource::make($module), trans('messages.success.store'), 201);
    }

    public function show(Module $module)
    {
        $module = $this->moduleService->get($module, ['country', 'branch']);
        return $this->success(ModuleResource::make($module), 'messages.success.show', 200);
    }

    public function update(UpdateModuleRequest $request, Module $module)
    {
        $validatedData = $request->safe()->all();
        $this->moduleService->update($validatedData, $module, ['country', 'branch']);
        return $this->success($module, 'messages.success.update', 200);

    }

    public function destroy(Module $module)
    {
        $this->moduleService->delete($module);
        return $this->success([], 'messages.success.delete', 200);
    }
}
