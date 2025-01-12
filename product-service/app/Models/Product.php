<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\DTO\ProductDTO;
use Decimal\Decimal;

/**
 * @property-read int     $id
 * @property      string  $name
 * @property      string  $description
 * @property      decimal $price
 */
final class Product extends Model
{
    /** @var string[] $attributes */
    protected $attributes = [
        'name' => '',
        'description' => '',
    ];

    public function toDTO(): ProductDTO
    {
        return new ProductDTO(
            $this->id,
            $this->name,
            $this->description,
            new Decimal($this->price),
        );
    }
}
