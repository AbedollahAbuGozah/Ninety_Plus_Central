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
        $modules = $country->modules;
        return $this->success(ModuleResource::collection($modules), trans('messages.success.index'), 200);
    }

    public function store(StoreModuleRequest $request, Country $country)
    {

        $validatedData = $request->safe()->all();
        $module = $this->moduleService->create($validatedData, new Module());
        return $this->success(ModuleResource::make($module), trans('messages.success.store'), 201);
    }

    public function show(Module $module)
    {
        return $this->success(ModuleResource::make($module), 'messages.success.show', 200);
    }

    public function update(UpdateModuleRequest $request, Module $module)
    {
        $validatedData = $request->safe()->all();
        $this->moduleService->update($validatedData, $module);
        return $this->success($module, 'messages.success.update', 200);

    }

    public function destroy(Module $module)
    {
        $this->moduleService->delete($module);
        return $this->success($module, 'messages.success.delete', 200);
    }
}
