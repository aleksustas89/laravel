<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Structure;
use App\Models\StructureMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class StructureController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public static $path = '/admin/structure/'; 

    public function index()
    {
        $parent = Arr::get($_REQUEST, 'parent_id', 0);

        $aResult = [];

        foreach (Structure::where('parent_id', $parent)->orderBy('sorting', 'desc')->get() as $oStructure) {

            $aStructure = [];
            $aStructure["id"] = $oStructure->id;
            $aStructure["name"] = $oStructure->name;
            $aStructure["menu_id"] = $oStructure->structure_menu_id;
            $aStructure["menu_name"] = $oStructure->structure_menu_id > 0 ? $oStructure->StructureMenu->name : '';
            $aStructure["active"] = $oStructure->active;
            $aStructure["indexing"] = $oStructure->indexing;
            $aStructure["url"] = $oStructure->getPath();
            $aStructure["subCount"] = $oStructure->getChildCount();

            $aResult[] = $aStructure;
        }

        return view('admin.structure.index', [
            'structures' => $aResult,
            'breadcrumbs' => self::breadcrumbs($parent > 0 ? Structure::find($parent) : []),
            'create' => self::$path . 'create' . ($parent > 0 ? '?parent_id=' . $parent : ''),
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $parent = Arr::get($_REQUEST, 'parent_id', 0);

        return view('admin.structure.create', [
            'breadcrumbs' => self::breadcrumbs($parent > 0 ? Structure::find($parent) : false, [], true),
            'parent_id' => $parent,
            'menus' => StructureMenu::get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        return $this->saveStructure($request);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Structure $structure)
    {
        return view('admin.structure.edit', [
            'structure' => $structure,
            'breadcrumbs' => self::breadcrumbs($structure, [], true),
            'url' => $structure->getPath(),
            'menus' => StructureMenu::get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Structure $structure)
    {
        return $this->saveStructure($request, $structure);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Structure $structure)
    {
        $structure->delete();

        return redirect()->back()->withSuccess('Структура был успешно удалена!');
    }

    /**
     * get breadcrumbs for structure
     * @param structure - object structure|false
    */
    public static function breadcrumbs($structure, $aResult = array(), $lastItemIsLink = false)
    {


        if ($structure) {

            $Result["name"] = $structure->name;
            $Result["url"] = '';
            if ($lastItemIsLink && count($aResult) == 0) {
                $Result["url"] = self::$path . '?parent_id=' . $structure->id;
            } else if ($lastItemIsLink === false && count($aResult) > 0 && $structure->parent_id == 0) {
                $Result["url"] = self::$path . '?parent_id=' . $structure->id;
            }
            array_unshift($aResult, $Result);

            if ($structure->parent_id > 0) {
                return self::breadcrumbs(Structure::find($structure->parent_id), $aResult, false);
            } else {

                $Result["url"] = self::$path;
                $Result["name"] = 'Разделы структуры';

                array_unshift($aResult, $Result);

                return $aResult;
            }

        } else {

            $Result["name"] = 'Разделы структуры';
            if ($lastItemIsLink) {
                $Result["url"] = self::$path;
            }

            array_unshift($aResult, $Result);

            return $aResult;
        }



        return $aResult;
    }

    /**
     * when saving structure
     * return false if structure exists
    */
    protected function checkStructure($id, $parent_id, $path)
    {
        $check = DB::table('structures')
                    ->where('parent_id', $parent_id)
                    ->where('path', $path)
                    ->whereNot('id', $id)
                    ->first();
        if (!is_null($check)) {
            return false;
        }
        return true;
    }

    protected function saveStructure(Request $request, $structure = false) 
    {
        if (!$structure) {
            $structure = new Structure();
        }

        if (!empty($request->path)) {
            if ($this->checkStructure(($structure->id ?? 0), $request->parent_id ?? 0, $request->path)) {
                $structure->name = $request->name;
                $structure->seo_title = $request->seo_title;
                $structure->seo_description = $request->seo_description;
                $structure->seo_keywords = $request->seo_keywords;
                $structure->path = $request->path;
                $structure->structure_menu_id = $request->structure_menu_id;
                $structure->sorting = $request->sorting;
                $structure->active = $request->active == 'on' ? 1 : 0;
                $structure->indexing = $request->indexing == 'on' ? 1 : 0;
                $structure->text = $request->text;
                $structure->parent_id = $request->parent_id ?? 0;
        
                $structure->save();
        
                $message = 'Структура была успешно обновлена!';
        
                if (Arr::get($_REQUEST, 'apply') > 0) {
                    return redirect()->to(self::$path . ($structure->parent_id > 0 ? '?parent_id=' . $structure->parent_id : ''))->withSuccess($message);
                } else {
                    return redirect()->back()->withSuccess($message);
                }
            } else {
                return redirect()->back()->withError("Структура с таким путем уже создана!");
            }
        } else {
            return redirect()->back()->withError("Заполните поле path!");
        } 
    }

}
