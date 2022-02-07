<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function getStockHistory($id)
    {

        return self::where('booK_id', $id)->orderBy('created_at', 'desc')->get();
    }

}
