<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\PackageRequest;
use App\Http\Resources\PackageResource;
use App\Models\Package;
use App\Models\Module;
use App\services\PackageService;

class PackageController extends BaseController
{

    public function __construct(private readonly PackageService $packageService)
    {

    }

    public function index(PackageRequest $request, Module $module)
    {
        $packages = Package::query()->with(['module'])
            ->filter()
            ->sort();

        return $this->success(PackageResource::collection($packages, $request->boolean('paginate'), $request->get('page_size')), trans('messages.success.index'), 200);
    }

    public function store(PackageRequest $request, Module $module)
    {
        $validatedData = $request->safe()->all();
        $package = $this->packageService->create($validatedData, new Package(), ['module', 'chapters']);
        return $this->success(PackageResource::make($package), trans('messages.success.store'), 201);
    }

    public function show(PackageRequest $request, Package $package)
    {
        logger(__METHOD__);
        $package = $this->packageService->get($package, ['module', 'chapters']);
        return $this->success(PackageResource::make($package), trans('messages.success.index'), 200);
    }

    public function update(PackageRequest $request, Package $package)
    {
        $validatedData = $request->safe()->all();
        $package = $this->packageService->update($validatedData, $package, ['module', 'chapters']);
        return $this->success(PackageResource::make($package), trans('messages.success.update'), 200);
    }

    public function destroy(PackageRequest $request, Package $package)
    {
        $package->delete();
        return $this->success([], trans('messages.success.delete'), 200);
    }


}
