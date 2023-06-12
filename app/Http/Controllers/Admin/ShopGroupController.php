<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopGroup;
use App\Models\Str;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;

class ShopGroupController extends Controller
{

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parent = Arr::get($_REQUEST, 'parent_id', 0);

        return view('admin.shop.group.create', [
            'breadcrumbs' => self::breadcrumbs($parent > 0 ? ShopGroup::find($parent) : false, [], true),
            'parent_id' => $parent,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->saveShopGroup($request);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShopGroup $shopGroup)
    {
        return view('admin.shop.group.edit', [
            'shopGroup' => $shopGroup,
            'breadcrumbs' => self::breadcrumbs($shopGroup),
            'store_path' => Shop::$store_path,
        ]);
     }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShopGroup $shopGroup)
    {
        return $this->saveShopGroup($request, $shopGroup);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShopGroup $shopGroup)
    {
        $shopGroup->delete();

        return redirect()->back()->withSuccess("Группа была успешно удалена!");
    }

    public function saveShopGroup (Request $request, $shopGroup = false) 
    {
        if (!$shopGroup) {
            $shopGroup = new ShopGroup();
        }

        //$request->validate([
            // 'name' => ['required', 'string', 'max:255'],
            // 'seo_title' => ['required', 'string', 'max:255'],
            // 'seo_keywords' => ['required', 'string', 'max:255'],
            // 'path' => ['required', 'string', 'max:255'],
        //]);

        $shopGroup->name = $request->name;
        $shopGroup->description = $request->description;
        $shopGroup->active = $request->active == 'on' ? 1 : 0;
        $shopGroup->parent_id = $request->parent_id ?? 0;
        $shopGroup->sorting = $request->sorting ?? 0;
        $shopGroup->path = !empty(trim($request->path)) ? $request->path : Str::transliteration($request->name);
        $shopGroup->seo_title = $request->seo_title;
        $shopGroup->seo_description = $request->seo_description;
        $shopGroup->seo_keywords = $request->seo_keywords;

        $shopGroup->save();

        $Filesystem = new Filesystem();

        if (!file_exists('../storage/app' . $shopGroup->path())) {
            $Filesystem->makeDirectory('../storage/app' . $shopGroup->path(), 0755, true);
        }

        $bFile = FALSE;
        if ($request->hasFile("image_large")) {
            $bFile = TRUE;
            $sFilename = 'image_large.' . $request->file('image_large')->getClientOriginalExtension();
            $request->file('image_large')->storeAs(Shop::$store_path . "group_" . $shopGroup->id, $sFilename);
            $shopGroup->image_large = $sFilename;
        }

        if ($request->hasFile("image_small")) {
            $bFile = TRUE;
            $sFilename = 'image_small.' . $request->file('image_small')->getClientOriginalExtension();
            $request->file('image_small')->storeAs(Shop::$store_path . "group_" . $shopGroup->id, $sFilename);
            $shopGroup->image_small = $sFilename;
        }

        if ($bFile) {
            $shopGroup->save();
        }

        $message = "Группа была успешно сохранена!";

        if (Arr::get($_REQUEST, 'apply') > 0) {
            return redirect()->to(ShopController::$path . ($shopGroup->parent_id > 0 ? '?parent_id=' . $shopGroup->parent_id : ''))->withSuccess($message);
        } else {
            return redirect()->back()->withSuccess($message);
        }
            
    }


    public function deleteImage ($id, $field)
    {

        $oShopGroup = ShopGroup::find($id);
        if ($oShopGroup) {

            Storage::delete(Shop::$store_path . 'group_' . $oShopGroup->id . '/' . $oShopGroup->$field);

            $oShopGroup->$field = '';
            $oShopGroup->save();

            return response()->json('true');
        } else {
            return response()->json('false');
        }

        return response()->json($id);
    }

    public static function breadcrumbs($shopGroup, $aResult = array(), $lastItemIsLink = false)
    {
        if ($shopGroup) {

            $Result["name"] = $shopGroup->name;
            $Result["url"] = '';
            if ($lastItemIsLink && count($aResult) == 0) {
                $Result["url"] = ShopController::$path . '?parent_id=' . $shopGroup->id;
            } else if ($lastItemIsLink === false && count($aResult) > 0 && $shopGroup->parent_id == 0) {
                $Result["url"] = ShopController::$path . '?parent_id=' . $shopGroup->id;
            }
            array_unshift($aResult, $Result);

            if ($shopGroup->parent_id > 0) {
                return self::breadcrumbs(ShopGroup::find($shopGroup->parent_id), $aResult, false);
            } else {

                $Result["url"] = ShopController::$path;
                $Result["name"] = 'Интернет-магазин';

                array_unshift($aResult, $Result);

                return $aResult;
            }

        } else {
            //
            $Result["url"] = ShopController::$path;
            $Result["name"] = 'Интернет-магазин';

            array_unshift($aResult, $Result);

            return $aResult;
        }

        return $aResult;
    }
}
