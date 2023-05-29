<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopItemProperty;
use Illuminate\Http\Request;

class ShopItemPropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.shop.item.property.index', [
            'breadcrumbs' => [],
            'properties' => ShopItemProperty::get()


        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ShopItemProperty $shopItemProperty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShopItemProperty $shopItemProperty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShopItemProperty $shopItemProperty)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShopItemProperty $shopItemProperty)
    {
        //
    }
}
