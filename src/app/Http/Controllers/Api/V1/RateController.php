<?php

namespace App\Http\Controllers\Api\V1;

use App\Facades\NinetyPlusCentralFacade;
use App\Http\Requests\RateRequest;
use App\Http\Resources\RateResource;
use App\Models\Rate;
use App\services\RateService;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class RateController extends BaseController
{
    public function __construct(protected RateService $rateService)
    {

    }

    public function index(RateRequest $request, $rateableType, $rateableId)
    {
        if (!($rateable_model = NinetyPlusCentralFacade::getRateableModel($rateableType))) {
            throw new RouteNotFoundException();
        }

        $rateable = $rateable_model::findOrFail($rateableId);

        return $this->success(RateResource::collection($rateable->comments), 'message.index.success', 201);

    }

    public function store(RateRequest $request, $rateableType, $rateableId)
    {
        if (!($rateable_model = NinetyPlusCentralFacade::getRatableModel($rateableType))) {
            throw new RouteNotFoundException();
        }


        $validatedData = $request->safe()->all();;
        $rateable = $rateable_model::findOrFail($rateableId);
        $rate = $rateable->addRate($validatedData);

        return $this->success(RateResource::make($rate), 'message.store.success', 201);
    }

    public function show(Rate $rate)
    {
        return $this->success(RateResource::make($rate), 'message.show.success', 201);
    }

    public function update(RateRequest $request, Rate $rate)
    {
        $validatedData = $request->safe()->all();
        $this->rateService->update($validatedData, $rate);
        return $this->success(RateResource::make($rate), 'message.update.success', 201);
    }

    public function destroy(Rate $rate)
    {
        $this->rateService->delete($rate);
        return $this->success([], 'message.delete.success', 201);
    }
}
