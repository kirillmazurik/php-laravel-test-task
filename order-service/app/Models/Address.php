<?php

declare(strict_types=1);

namespace App\Models;

use App\DTO\AddressDTO;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int    $id
 * @property      string $address
 */
final class Address extends Model
{
    /** @var string[] $attributes */
    protected $attributes = [
        'address' => '',
    ];

    public function toDTO(): AddressDTO
    {
        return new AddressDTO(
            $this->id,
            $this->address,
        );
    }
}
