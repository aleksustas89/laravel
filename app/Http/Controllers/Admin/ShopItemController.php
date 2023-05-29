<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\ShopItem;
use App\Models\ShopItemImage;
use App\Models\ShopCurrency;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Models\ShopGroup;

class ShopItemController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parent = Arr::get($_REQUEST, 'parent_id', 0);

        return view('admin.shop.item.create', [
            'breadcrumbs' => ShopGroupController::breadcrumbs($parent > 0 ? ShopGroup::find($parent) : false, [], true),
            'parent_id' => $parent,
            'currencies' => ShopCurrency::orderBy('sorting', 'asc')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->saveShopItem($request);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShopItem $shopItem)
    {

        return view('admin.shop.item.edit', [
            'shopItem' => $shopItem,
            'images' => $shopItem->getImages(),
            'breadcrumbs' => [], // self::getBreadcrumbs($shopGroup),
            'breadcrumbs' => ShopGroupController::breadcrumbs($shopItem->ShopGroup, [], true),
            'store_path' => Shop::$store_path,
            'currencies' => ShopCurrency::orderBy('sorting', 'asc')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShopItem $shopItem)
    {

        return $this->saveShopItem($request, $shopItem);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShopItem $shopItem)
    {

        $shopItem->delete();

        return redirect()->back()->withSuccess("Товар был успешно удален!");
    }

    public function saveShopItem (Request $request, $shopItem = false) 
    {

        $oShop = Shop::find(Shop::$shop_id);

        if (!$shopItem) {
            $shopItem = new ShopItem();
        }

        if (!empty($request->path)) {

            //$request->validate([
                // 'name' => ['required', 'string', 'max:255'],
                // 'seo_title' => ['required', 'string', 'max:255'],
                // 'seo_keywords' => ['required', 'string', 'max:255'],
                // 'path' => ['required', 'string', 'max:255'],
            //]);
    
            $shopItem->name = $request->name;
            $shopItem->description = $request->description;
            $shopItem->text = $request->text;
            $shopItem->active = $request->active == 'on' ? 1 : 0;
            $shopItem->indexing = $request->indexing == 'on' ? 1 : 0;
            $shopItem->shop_group_id = $request->shop_group_id ?? 0;
            $shopItem->sorting = $request->sorting ?? 0;
            $shopItem->marking = $request->marking;
            $shopItem->path = $request->path;
            $shopItem->seo_title = $request->seo_title;
            $shopItem->seo_description = $request->seo_description;
            $shopItem->seo_keywords = $request->seo_keywords;
            $shopItem->price = $request->price ?? 0;
            $shopItem->shop_currency_id = $request->shop_currency_id;
    
            $shopItem->save();

            if (isset($request->image)) {

                for ($i = 0; $i < count($request->image); $i++) {

                    $oShopItemImage = new ShopItemImage();
                    $oShopItemImage->shop_item_id   = $shopItem->id;
                    $oShopItemImage->image_original = $request->image[$i]->getClientOriginalName();
                    $oShopItemImage->save();

                    //сохраняем оригинал
                    $request->image[$i]->storeAs($shopItem->path(), $request->image[$i]->getClientOriginalName());

                    //большое изображение
                    $image_large = Image::make(Storage::path($shopItem->path()) . $request->image[$i]->getClientOriginalName());
                    if ($oShop->preserve_aspect_ratio == 1) {
                        $image_large->resize($oShop->image_large_max_width, $oShop->image_large_max_height, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });
                    } else {
                        $image_large->fit($oShop->image_large_max_width, $oShop->image_large_max_height);
                    }
                    $sImageLargeName = 'image_large'. $oShopItemImage->id .'.' . $request->image[$i]->getClientOriginalExtension();
                    $image_large->save(Storage::path($shopItem->path()) . $sImageLargeName);
                    $oShopItemImage->image_large = $sImageLargeName;

                    //превью
                    $image_small = Image::make(Storage::path($shopItem->path()) . $request->image[$i]->getClientOriginalName());
                    if ($oShop->preserve_aspect_ratio_small == 1) {
                        $image_small->resize($oShop->image_small_max_width, $oShop->image_small_max_height, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });
                    } else {
                        $image_small->fit($oShop->image_small_max_width, $oShop->image_small_max_height);
                    }
                    $sImageSmallName = 'image_small'. $oShopItemImage->id .'.' . $request->image[$i]->getClientOriginalExtension();
                    $image_small->save(Storage::path($shopItem->path()) . $sImageSmallName);
                    $oShopItemImage->image_small = $sImageSmallName;

                    $oShopItemImage->save();

                }
            }

            $message = "Товар был успешно сохранен!";
    
            if ($request->apply) {
                return redirect()->to(ShopController::$path . ($shopItem->shop_group_id > 0 ? '?parent_id=' . $shopItem->shop_group_id : ''))->withSuccess($message);
            } else {
               return redirect()->back()->withSuccess($message);
            }
            
        } else {
            return redirect()->back()->withError("Заполните поле path!");
        } 
    }

    public function deleteImage($id) 
    {

        $response = false;

        $oShopItemImage = ShopItemImage::find($id);
        if (!is_null($oShopItemImage)) {

            $oShopItemImage->delete();

            $response = true;
        }

        return response()->json($response);
    }

}
