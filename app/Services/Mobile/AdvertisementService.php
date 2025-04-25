<?php

namespace App\Services\Mobile;
use App\Filters\Mobile\AdvertisementFilter;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\{
    Advertisement,
    User,
    AdvertisementAttribute
};
use App\Traits\HasFiles;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Notifications\Dasboard\NotofcationAddAds;
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

                    if ($image instanceof \Illuminate\Http\UploadedFile) {

                        $url = $this->saveFile($image, 'ads');
                        $ad->images()->create(['path' => $url]);
                    }
                }
            }


            if (isset($request['attributes']) && is_array($request['attributes'])) {
                foreach ($request['attributes'] as $attributeKey => $attribute) {
                    if (is_array($attribute)) {

                        //throw new Exception("not array", 1);
                        foreach ($attribute as $key => $value) {
                            if (is_array($value)) {
                                foreach ($value as $innerKey => $innerValue) {
                                    AdvertisementAttribute::create([
                                        'advertisement_id' => $ad->id,
                                        'title' => $attributeKey,
                                        'name' => $key,
                                        'value' => $innerValue,
                                    ]);
                                }
                            } else {
                                AdvertisementAttribute::create([
                                    'advertisement_id' => $ad->id,
                                    'title' => $attributeKey,
                                    'name' => $key,
                                    'value' => $value,
                                ]);
                            }
                        }
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

            $admins = User::role(['admin', 'supervisour'], 'api')->get();
            foreach($admins as $admin){
                $admin->notify(new NotofcationAddAds("قام {$user->name} باضافة اعلان جديد: {$ad->title}"));
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
