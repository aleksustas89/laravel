<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ShopItemImage;
use App\Models\Shop;

class ShopItem extends Model
{
    use HasFactory;

    public function ShopGroup()
    {
        return $this->belongsTo(ShopGroup::class);
    }

    public function ShopItemImages()
    {
        return $this->hasMany(ShopItemImage::class);
    }

    public function getImages()
    {
        $aReturn = [];

        foreach ($this->ShopItemImages as $ShopItemImage) {
            if (!empty($ShopItemImage->image_large) || !empty($ShopItemImage->image_small)) {
                $aReturn[$ShopItemImage->id]["image_large"] = !empty($ShopItemImage->image_large) ? $this->path() . $ShopItemImage->image_large : '';
                $aReturn[$ShopItemImage->id]["image_small"] = !empty($ShopItemImage->image_small) ? $this->path() . $ShopItemImage->image_small : '';
            }
        }

        return $aReturn;
    }

    public function path()
    {

        return Shop::$store_path . 'group_' . $this->shop_group_id . '/item_' . $this->id . '/';
    }

    public function url()
    {

        return Shop::path() . $this->ShopGroup->path() . $this->path;
    }

    public function delete()
    {
        foreach ($this->ShopItemImages as $ShopItemImage) {
            $ShopItemImage->delete();
        }

        parent::delete();
    }
}
