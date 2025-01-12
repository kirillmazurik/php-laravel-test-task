<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Decimal\Decimal;

/**
 * @property-read int     $id
 * @property      int     $order_id
 * @property      int     $product_id
 * @property      int     $count
 * @property      Decimal $sell_price
 * @property      Decimal $total
 */
final class OrderProducts extends Model
{
    use SoftDeletes;
}
