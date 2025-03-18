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

    public function update(FormRequest $request, Advertisement $ad)
    {
        $ad->update($request->except('attributes'));

        if ($request->has('attributes') && is_array($request->attributes)) {
            foreach ($request->attributes as $attribute) {
                AdvertisementAttribute::updateOrCreate(
                    [
                        'advertisement_id' => $ad->id,
                        'name' => $attribute['name'],
                    ],
                    [
                        'value' => $attribute['value'],
                    ]
                );
            }
        }
        return $ad;
    }


    public function delete(Advertisement $advertisement)
    {
        
    }
}
