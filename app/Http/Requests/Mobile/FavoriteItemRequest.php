<?php

namespace App\Http\Requests\Mobile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\{DB,Auth};
class FavoriteItemRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'ads_id'=>'required',
            //'lists'=>'required|array',
            //'lists.*' => 'required|numeric|exists:favorite_lists,id,user_id,'.Auth::id(),
        ];
    }
}
