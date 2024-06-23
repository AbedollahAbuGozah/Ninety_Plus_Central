<?php

namespace App\Http\Resources;

use App\Facades\NinetyPlusCentralFacade;
use App\Models\User;
use App\services\CurrentUserService;
use Illuminate\Http\Request;

class UserResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $profileImagePath = $this->getFirstMediaUrl(User::PROFILE_IMAGE_MEDIA_COLLECTION);

        $common = [
            'user_id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,

            'city_id' => $this->whenLoaded('city', function () {
                return [
                    'id' => $this->city_id,
                    'name' => $this->city->name
                ];
            }, $this->city_id),

            'courses' => $this->whenLoaded('courses', function () {
                return $this->courses->groupBy('status')->map(function ($groupedCourses) {
                    return CourseResource::collection($groupedCourses);
                });
            }),

            'email_verified' => (bool)$this->email_verified_at,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date,
            'phone' => $this->phone,
            'profile_image' => $this->when($profileImagePath, fn() => $profileImagePath),
            'roles' => $this->roles->pluck('name'),
            'branch' => $this->whenLoaded('branch', fn() => $this->branch->select('id', 'name'), $this->brnach_id),
            'permissions' => $this->when(auth()->check(), fn() => ((new CurrentUserService())->getPermissions())),
        ];
        return array_merge($common, $this->instructorData());
    }

    private function instructorData()
    {
        if (!$this->isInstructor()) {
            return [];
        }

        return [
            'total_earnings' => $this->resolveUser()->total_earnings,
            'today_earnings' => $this->resolveUser()->today_earnings,
            'number_of_sales' => $this->resolveUser()->number_of_sales,
            'about' => $this->about,
            'balance' => rand(1 ,300),
            'withdraw_balance' => rand(1, 20000),
            'rate' => NinetyPlusCentralFacade::calcRatableRate($this->resolveUser()),
        ];
    }
}
