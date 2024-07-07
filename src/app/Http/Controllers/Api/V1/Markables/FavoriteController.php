<?php

namespace App\Http\Controllers\Api\V1\Markables;

use App\Facades\NinetyPlusCentralFacade;
use App\Http\Controllers\Api\V1\BaseController;
use Illuminate\Http\Request;
use Maize\Markable\Models\Favorite;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class FavoriteController extends BaseController
{
    public function index(Request $request, $favorableType)
    {
        if (!($favorableModelClass = NinetyPlusCentralFacade::getFavoriteModel($favorableType))) {
            throw new RouteNotFoundException();
        }
        $favorites = $favorableModelClass::whereHasFavorite(auth()->user());
        $favoriteResource = NinetyPlusCentralFacade::getModelResource($favorableModelClass);
        return $this->success($favoriteResource::collection($favorites, $request->boolean('paginate'), $request->get('page_size')), 'message.index.success', 200);
    }

    public function markAsFavorite(Request $request, $favorableType, $favorableId)
    {
        if (!($favorableModelClass = NinetyPlusCentralFacade::getFavoriteModel($favorableType))) {
            throw new RouteNotFoundException();
        }


        $favorableModel = $favorableModelClass::findOrFail($favorableId);

        Favorite::add($favorableModel, auth()->user());

        return $this->success([], 'message.store.success', 201);
    }

    public function unMarkAsFavourite(Request $request, $favorableType, $favorableId)
    {
        if (!($favorableModelClass = NinetyPlusCentralFacade::getFavoriteModel($favorableType))) {
            throw new RouteNotFoundException();
        }

        $favorableModel = $favorableModelClass::findOrFail($favorableId);

        Favorite::remove($favorableModel, auth()->user());

        return $this->success([], 'message.store.success', 200);
    }


}
