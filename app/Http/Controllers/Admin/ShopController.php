<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\ShopGroup;
use App\Models\ShopItem;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\ShopItemImage;

class ShopController extends Controller
{

    public static $path = '/admin/shop/'; 

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $oShop = Shop::find(Shop::$shop_id);
        if (!is_null($oShop->id) && $oShop->active == 1) {

            $parent = Arr::get($_REQUEST, 'parent_id', 0);

            $aResultGroup = [];
            foreach (ShopGroup::where('parent_id', $parent)->orderBy('sorting', 'desc')->get() as $oShopGroup) {
                $aShopGroup = [];
                $aShopGroup["id"] = $oShopGroup->id;
                $aShopGroup["name"] = $oShopGroup->name;
                $aShopGroup["active"] = $oShopGroup->active;
                $aShopGroup["indexing"] = $oShopGroup->indexing;
                $aShopGroup["url"] = '/' . $oShop->path .'/'. $oShopGroup->path();
                $aShopGroup["subCount"] = $oShopGroup->getChildCount();
    
                $aResultGroup[] = $aShopGroup;
            }

            $aResultItems = [];
            foreach (ShopItem::where('shop_group_id', $parent)->orderBy('sorting', 'desc')->get() as $oShopItem) {
                $aShopItem = [];
                $aShopItem["id"] = $oShopItem->id;
                $aShopItem["name"] = $oShopItem->name;
                $aShopItem["active"] = $oShopItem->active;
                $aShopItem["marking"] = $oShopItem->marking;
                $aShopItem["indexing"] = $oShopItem->indexing;
                $aShopItem["url"] = $oShopItem->url();

                $oShopItemImage = ShopItemImage::where("shop_item_id", "=", $oShopItem->id)
                                    ->orderBy('sorting', 'Asc')
                                    ->orderBy('id', 'Asc')
                                    ->first();

                $aShopItem["image_small"] = !is_null($oShopItemImage) ? $oShopItem->path() . $oShopItemImage->image_small : '';
    
                $aResultItems[] = $aShopItem;
            }

    
            return view('admin.shop.index', [
                'shop_groups' => $aResultGroup,
                'shop_items' => $aResultItems,
                'breadcrumbs' => ShopGroupController::breadcrumbs($parent > 0 ? ShopGroup::find($parent) : false),
                'createGroup' => '/admin/shopGroup/create' . ($parent > 0 ? '?parent_id=' . $parent : ''),
                'createItem' => '/admin/shopItem/create' . ($parent > 0 ? '?parent_id=' . $parent : ''),
            ]);

        } else {
            return "The store is off";
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shop $shop)
    {
        return view('admin.shop.edit', [
            'shop' => $shop,
            'breadcrumbs' => self::breadcrumbs(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shop $shop)
    {

        $shop->name = $request->name;
        $shop->description = $request->description;
        $shop->email = $request->email;
        $shop->active = $request->active == 'on' ? 1 : 0;
        $shop->path = $request->path;
        $shop->items_on_page = $request->items_on_page;
        $shop->seo_title = $request->seo_title;
        $shop->seo_description = $request->seo_description;
        $shop->seo_keywords = $request->seo_keywords;

        $shop->image_large_max_width = $request->image_large_max_width;
        $shop->image_large_max_height = $request->image_large_max_height;
        $shop->image_small_max_width = $request->image_small_max_width;
        $shop->image_small_max_height = $request->image_small_max_height;
        $shop->preserve_aspect_ratio = $request->preserve_aspect_ratio == 'on' ? 1 : 0;
        $shop->preserve_aspect_ratio_small = $request->preserve_aspect_ratio_small == 'on' ? 1 : 0;
        $shop->group_image_large_max_width = $request->group_image_large_max_width;
        $shop->group_image_large_max_height = $request->group_image_large_max_height;
        $shop->group_image_small_max_width = $request->group_image_small_max_width;
        $shop->group_image_small_max_height = $request->group_image_small_max_height;
        $shop->preserve_aspect_ratio_group = $request->preserve_aspect_ratio_group == 'on' ? 1 : 0;
        $shop->preserve_aspect_ratio_group_small = $request->preserve_aspect_ratio_group_small == 'on' ? 1 : 0;

        $shop->save();

        $message = 'Настройки интернет-магазина были успешно обновлены!';

        if (Arr::get($_REQUEST, 'apply') > 0) {
            return redirect()->to(self::$path)->withSuccess($message);
        } else {
            return redirect()->back()->withSuccess($message);
        }
    }

    public static function breadcrumbs()
    {
        $aResult[0]["name"] = 'Интернет-магазин';
        $aResult[0]["url"] = self::$path;

        return $aResult;
    }

}
