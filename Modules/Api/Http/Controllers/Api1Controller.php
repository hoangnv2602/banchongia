<?php

namespace Modules\Api\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Api\Entities\Menu1;

class Api1Controller extends Controller
{
    private $menu;
    public function __construct() {
        $this->menu = new Menu1;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {   
        return view('api::index');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function getPanigationMenu()
    {   
        return $this->menu->get_menu();
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function insert()
    {   
        return $this->menu->insert();
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return $this->menu->create_menu();
    }

    public function check()
    {
        return $this->menu->check();
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('api::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('api::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
