<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopItemPropertyForGroup extends Model
{
    use HasFactory;

    public function getGroupsForProperty(ShopItemProperty $shopItemProperty)
    {

        $aResult = [];

        $aPropertiesForGroup = ShopItemPropertyForGroup::where('shop_item_property_id', $shopItemProperty->id)->get();

        foreach ($aPropertiesForGroup as $property) {
            $aResult[] = $property->shop_group_id;
        }

        return $aResult;
    }
}
