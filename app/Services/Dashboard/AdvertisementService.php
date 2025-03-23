<?php

namespace App\Services\Dashboard;

use App\Models\Advertisement;
use App\Models\AdvertisementAttribute;
use App\Traits\HasFiles;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class AdvertisementService.
 */
class AdvertisementService
{
    use HasFiles;

    public function update($request, Advertisement $ad)
    {
        $ad->update($request->except('attributes'));

        if ($request->has('attributes')) {
            foreach ($request->only('attributes') as $attributes) {
                foreach ($attributes as $key => $value) {
                    $attr = AdvertisementAttribute::find($key);
                    $attr->update(['value' => $value]);
                }
            }
        }

        $ad->load('attributes');

        return $ad;
    }


    public function destroy(Advertisement $advertisement)
    {
        $images = $advertisement->images;
        foreach ($images as $image) {
            $this->deleteFile($image->path);
        }
        $advertisement->delete();
        return true;
    }

}

/*public function update($request, Advertisement $ad)
{
    $ad->update($request->except('attributes'));

    if ($request->has('attributes')) {
        foreach ($request->only('attributes') as $attributes) {
            foreach ($attributes as $key => $value) {
                $attr = AdvertisementAttribute::find($key);
                if($attr){
                $attr->update(['value' => $value]);}
            }
        }
    }
    $ad->load('attributes');
    return $ad;
}*/
