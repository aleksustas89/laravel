<?php

use Illuminate\Support\Facades\Route;
use App\Models\Structure;
//use App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\StructureController;
use App\Http\Controllers\Admin\SiteuserController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Admin\ShopGroupController;
use App\Http\Controllers\Admin\ShopItemController;
use App\Http\Controllers\Admin\ShopItemPropertyController;
use App\Http\Controllers\Admin\ShopCurrencyController;
use App\Models\Shop;
use App\Models\ShopGroup;
use App\Models\ShopItem;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth_force_unactive', 'auth'], 'namespace' => 'App\Http\Controllers'], function(){
    
    Route::middleware(['role:user'])->prefix('admin')->group(function() {
        Route::get('/', [App\Http\Controllers\Admin\HomeController::class, 'index'])->name('homeAdmin');
        Route::resource('structure', StructureController::class);
        Route::resource('siteuser',  SiteuserController::class);
        Route::resource('user',  UserController::class);
        Route::resource('shop',  ShopController::class);
        Route::resource('shopGroup',  ShopGroupController::class);
        Route::resource('shopCurrency',  ShopCurrencyController::class);
        Route::resource('shopItem',  ShopItemController::class);
        Route::resource('shopItemProperty',  ShopItemPropertyController::class);

        //удаление картинок
        Route::get('/shopGroup/{id}/delete/{field}', [ShopGroupController::class, 'deleteImage']);
        Route::get('/deleteShopItemImage/{id}', [ShopItemController::class, 'deleteImage']);

        

    });

});

if (Schema::hasTable('structures')) {
    $oStructureController = App\Http\Controllers\StructureController::class;

    foreach (Structure::where('active', '1')->get() as $structure) {

        if ($sPath = $structure->getPath([], $activeAll = false)) {

            Route::view($sPath, 'structure', ['structure' => $structure]);
        }
    }
}

if (Schema::hasTable('shops')) {
    $oShop = Shop::find(Shop::$shop_id);
    if (!is_null($oShop->id) && $oShop->active == 1) {
        Route::view($oShop->path, 'shop/home', ['shop' => $oShop]);

        foreach (shopGroup::where('active', '1')->get() as $oShopGroup) {
            Route::view($oShop::path() . $oShopGroup->path(), 'shop/group', ['group' => $oShopGroup]);
        }

        foreach (shopItem::where('active', '1')->get() as $oShopItem) {
            Route::view($oShopItem->url(), 'shop/item', ['item' => $oShopItem]);
        }
    }
}

Route::group(['namespace' => 'App\Http\Controllers'], function(){
    // Route::get('users/login', 'Auth\LoginController@showLoginForm')->name('login');

    Route::view('user/login', 'user.login');
    Route::post('user/login', 'Auth\LoginController@login');

    Route::view('user/account', 'user.account');

});




