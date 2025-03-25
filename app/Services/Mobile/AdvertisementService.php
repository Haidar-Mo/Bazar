<?php

namespace App\Services\Mobile;
use App\Filters\Mobile\AdvertisementFilter;
use Illuminate\Support\Facades\DB;
use App\Models\{
    Advertisement,
    User,
    AdvertisementAttribute
};
use App\Traits\HasFiles;

/**
 * Class AdvertisementService.
 */
class AdvertisementService
{
    use HasFiles;


    public function __construct(protected AdvertisementFilter $advertisementFilter)
    {
    }


    public function create($request, User $user)
    {
        $data = $request->validated();
        return DB::transaction(function () use ($user, $request, $data) {

            $ad = $user->ads()->create([
                'city_id' => $data['city_id'],
                'category_id' => $data['category_id'],
                'title' => $data['title'],
                'type' => $data['type'],
                'currency_type' => $data['currency_type'],
                'negotiable' => $data['negotiable'],
                //'is_special' => $data['is_special'],
                'price' => $data['price'],
                'expiry_date' => now()->addDays(30),
            ]);
            if (isset($data['images'])) {
                $images = $request->file('images');
                foreach ($images as $image) {
                    $url = $this->saveFile($image, 'ads');
                    $ad->images()->create(['path' => $url]);
                }
            }
            if (isset($data['attributes']) && is_array($data['attributes'])) {
                foreach ($data['attributes'] as $attributeKey => $attribute) {
                    foreach ($attribute as $key => $value) {
                        AdvertisementAttribute::create([
                            'advertisement_id' => $ad->id,
                            'title' => $attributeKey,
                            'name' => $key,
                            'value' => $value,
                        ]);
                    }
                }
            }
            return $ad;
        });

    }



    public function indexWithFilter()
    {
        $query = Advertisement::query()->with('attributes');
        return $this->advertisementFilter->apply($query)->get();
    }
}
