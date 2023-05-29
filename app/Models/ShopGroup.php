<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopGroup extends Model
{
    use HasFactory;

    public function ShopItems()
    {
        return $this->hasMany(ShopItem::class);
    }

    public function path($aResult = array(), $activeAll = true)
    {
        
        // if ($this->path != '/') {
             array_unshift($aResult, $this->path);
        // }

        if ($this->parent_id > 0) {
            $oShopGroup = ShopGroup::where("id", $this->parent_id)->whereIn('active', $activeAll ? [0,1] : [1])->first();
            if (!is_null($oShopGroup)) {
                return $oShopGroup->path($aResult);
            }
            return false;
            
        } else {
            return $this->path != '/' ? implode("/", $aResult) . '/' : $this->path;
        }
    }



    public function getChildCount()
	{
		$count = 0;

		$aShopGroups = ShopGroup::where('parent_id', $this->id)->get();

		foreach ($aShopGroups as $aShopGroup)
		{
			$count++;
			$count += $aShopGroup->getChildCount();
		}

		return $count;
	}

    public function getChildId()
	{
		$count = [];

		$aShopGroups = ShopGroup::where('parent_id', $this->id)->get();

		foreach ($aShopGroups as $aShopGroup)
		{
			$count[] = $aShopGroup->id;
			$count[] = $aShopGroup->getChildId();
		}

		return $count;
	}

    public function delete()
    {

        $aShopGroups = ShopGroup::where('parent_id', $this->id)->get();

		foreach ($aShopGroups as $aShopGroup)
		{
            $aShopGroup->delete();
		}

        foreach ($this->ShopItems as $oShopItem) {
            $oShopItem->delete();
        }

        parent::delete();
    }
}
