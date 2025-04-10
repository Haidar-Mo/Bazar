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
use Illuminate\Support\Arr;

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
        while ($request['attributes'] && is_string($request['attributes'])) {
            $request['attributes'] = json_decode($request['attributes'], true);
        }

        $filteredData = Arr::except($request, ['images', 'attributes']);

        return DB::transaction(function () use ($user, $filteredData, $request) {
           
            $ad = $user->ads()->create($filteredData);


            if (isset($request['images']) && is_array($request['images'])) {
                foreach ($request['images'] as $image) {
                    if ($image instanceof IlluminateHttpUploadedFile) {
                        $url = $this->saveFile($image, 'ads');
                        $ad->images()->create(['path' => $url]);
                    }
                }
            }


            if (isset($request['attributes']) && is_array($request['attributes'])) {
                foreach ($request['attributes'] as $attributeKey => $attribute) {
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


            $subscription = $user->subscriptions()->where('status','=','running')->first();
            if ($subscription) {
                $subscription->decrement('number_of_ads');
                if ($subscription->number_of_ads <= 0) {
                    $subscription->update(['status' => 'ended']);
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
