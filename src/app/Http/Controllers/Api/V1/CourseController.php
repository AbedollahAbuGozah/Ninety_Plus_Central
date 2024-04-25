<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\Module;
use App\services\CourseService;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\AssignOp\Mod;

class CourseController extends Controller
{

    use HttpResponse;

    public function __construct(protected CourseService $courseService )
    {

    }
    public function index(Request $request, Module $module)
    {
        $courses = $module->courses()->with(['instructor', 'module'])->get();
        $this->success(CourseResource::make($courses), trans('messages.success.index'), 200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Module $module)
    {
        $validatedData = $request->safe()->all();
        $courses = $this->courseService->create($validatedData, $module);
        $this->success(CourseResource::make($courses), trans('messages.success.index'), 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
