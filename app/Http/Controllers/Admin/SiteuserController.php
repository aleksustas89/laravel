<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SiteuserController extends Controller
{

    public static $path = '/admin/siteuser/'; 

    protected $acceptedRoles = array('siteuser');

    public $collects = User::class;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $User = new User();

        return view('admin.siteuser.index', [
             'users' => $User->getUsersByRoles($this->acceptedRoles),
             'breadcrumbs' => self::getBreadcrumbs(),
             'create' => self::$path . 'create',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.siteuser.create', [
            'breadcrumbs' => $this->getBreadcrumbs(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
 
        return User::saveUser($request, false, self::$path, 2);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $siteuser)
    {
        return view('admin.siteuser.edit', [
            'user' => $siteuser,
            'breadcrumbs' => $this->getBreadcrumbs(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $siteuser)
    {
  
        return User::saveUser($request, $siteuser, self::$path, 2);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $siteuser)
    {

        return User::deleteUser($siteuser);
    }

    

    public static function getBreadcrumbs()
    {

        $aResult[0]["name"] = 'Клиенты';
        $aResult[0]["url"] = self::$path;

        return $aResult;
        
    }
}
