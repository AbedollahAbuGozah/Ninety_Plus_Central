<?php

namespace App\services;


use App\Models\Package;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use NinetyPlus;

class PackageService extends BaseService
{

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function postCreateOrUpdate($data, Model $package): void
    {
        $this->attachChaptersToPackage($data['chapters'] ?? [], $package);
        $this->savePackageCoverImage($package);
        $this->savePackageProperties($package);
        $package->save();
    }

    private function attachChaptersToPackage($chapters, Model $pacakge): void
    {
        if (empty($chapters)) {
            return;
        }

        $chapters = NinetyPlus::reformatForSync($chapters);
        $pacakge->chapters()->sync($chapters);
    }

    /**
     * @throws FileIsTooBig
     * @throws FileDoesNotExist
     */
    private function savePackageCoverImage($package): void
    {
        if (!request()->hasFile('cover_image')) {
            return;
        }

        $coverImage = $package->addMediaFromRequest('cover_image')
            ->withCustomProperties(['visibility' => 'public'])
            ->toMediaCollection(Package::PACKAGE_COVER_IMAGE_MEDIA_COLLECTION);

        Storage::disk('s3')->setVisibility($coverImage->getPath(), 'public');

        $props = $package->properties;
        $props['cover_image'] = $coverImage->getUrl();
        $package->properties = $props;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function savePackageProperties($package)
    {
        $props = $package->properties;
        $props['gifted_points'] = request()->get('gifted_points');
        $package->properties = $props;
    }


}


