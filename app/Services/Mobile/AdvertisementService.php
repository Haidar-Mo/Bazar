<?php

namespace App\Services\Mobile;
use Illuminate\Support\Facades\DB;
use App\Models\{
    Advertisement,
    User,
    AdvertisementAttribute
};
use App\Traits\HasFiles;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
/**
 * Class AdvertisementService.
 */
class AdvertisementService
{
    use HasFiles;
    public function create($request, User $user)
    {
        $data = $request->validated();
        //return $data;

        return DB::transaction(function () use ($user, $request, $data) {

            $ad = $user->ads()->create([
                'city_id' => $data['city_id'],
                'category_id' => $data['category_id'],
                'is_special' => $data['is_special'],
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
                foreach ($data['attributes'] as $key => $value) {
                    AdvertisementAttribute::create([
                        'advertisement_id' => $ad->id,
                        'name' => $key,
                        'value' => $value,
                    ]);
                }
            }
            return $ad;
        });

    }
}
