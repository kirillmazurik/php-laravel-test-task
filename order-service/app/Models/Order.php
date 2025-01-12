<?php

declare(strict_types=1);

namespace App\Models;

use App\DTO\OrderDTO;
use Decimal\Decimal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read int     $id
 * @property      int     $address_id
 * @property      decimal $total
 * @property-read Address $address
 * @property-read OrderProducts []$orderProducts
 */
final class Order extends Model
{
    use SoftDeletes;

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function orderProducts(): HasMany
    {
        return $this->hasMany(OrderProducts::class, 'order_id');
    }

    public function toDTO(): OrderDTO
    {
        $address = "";

        if ($this->address !== null) {
            $address = $this
                ->address
                ->address;
        }

        $orderProducts = [];

        if (count($this->orderProducts)) {
            /** OrderProducts $orderProduct **/
            foreach ($this->orderProducts as $orderProduct) {
                $orderProducts[] = $orderProduct->product_id;
            }
        }

        return new OrderDTO(
            $this->id,
            $this->address_id,
            $address,
            $orderProducts,
            new Decimal($this->total),
        );
    }
}
