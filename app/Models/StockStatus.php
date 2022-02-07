<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockStatus extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public static function getStockStatus($quantity)
    {

        $stock_status = self::where('min', '>=', $quantity)
            ->where('max', '<', $quantity)->first();

        if ($stock_status) {
            return $stock_status->message;
        } else {
            return 'Status unknown';
        }
    }
}
