<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CreateRequest extends FormRequest
{
    /**
     * @return mixed[]
     */
    public function rules(): array
    {
        return [
            'address_id' => [
                'required',
                'integer',
                'min:1',
            ],
            'products.*' => [
                'required',
                'array',
                'min:1',
            ],
            'products.*.product_id' => [
                'required',
                'integer',
                'min:1',
            ],
            'products.*.count' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
    }
}
