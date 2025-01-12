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
            'name' => [
                'required',
                'string',
            ],
            'description' => [
                'required',
                'string',
            ],
            'price' => [
                'required',
                'decimal:0,2',
                'min:0',
                'max:99999.99',
            ],
        ];
    }
}
