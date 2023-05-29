<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public static $path = '/admin/user/'; 

    protected $acceptedRoles = array('user');

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $User = new User();

        return view('admin.user.index', [
             'users' => $User->getUsersByRoles($this->acceptedRoles),
             'breadcrumbs' => self::getBreadcrumbs(),
             'create' => self::$path . 'create',
        ]);
    }

    /**php artisan make:controller Admin\ShopController --resource --model=Shop
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.create', [
            'breadcrumbs' => $this->getBreadcrumbs(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        return User::saveUser($request, false, self::$path, 3);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {

        return view('admin.user.edit', [
            'user' => $user,
            'breadcrumbs' => $this->getBreadcrumbs(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        return User::saveUser($request, $user, self::$path, 3);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {

        return User::deleteUser($user);
    }

    public static function getBreadcrumbs()
    {

        $aResult[0]["name"] = 'Сотрудники';
        $aResult[0]["url"] = self::$path;

        return $aResult;
        
    }
}
