<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    /**
     * dir where to upload images
    */
    public static $store_path = '/shop/';

    public static $shop_id = 1;

    public static function path()
    {
        $oShop = self::find(self::$shop_id);

        return '/' . $oShop->path . '/';
    }
}
