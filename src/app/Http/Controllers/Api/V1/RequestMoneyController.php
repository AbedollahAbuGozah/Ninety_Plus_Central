<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Http\Resources\RequestMoneyResource;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class RequestMoneyController extends BaseController
{
    public function requestMoney(Request $request)
    {
        $user = auth()->user();

        logger(__METHOD__);

        if (!$user->balance) {
            return $this->error(trans('message.request_money.error'), 400);

        }

        $props = $user->properties;
        $props['balance_info']['requested'] = 1;
        $props['balance_info']['requested_date'] = now()->toDateString();
        $user->properties = $props;
        $user->save();
        return $this->success([], trans('message.request_money.success'), 200);

    }

    public function index(Request $request)
    {
        $user = User::where('properties->balance_info->requested', 1);
        return $this->success(RequestMoneyResource::collection($user, $request->boolean('paginate'), $request->get('page_size')), trans('messages.success.index'), 200);
    }
}
