<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    public function index(Request $request)
    {

        $menus = Menu::orderBy('name');  // Get cities with state information and paginate get();  // Get cities with state information
        
        if ($request->get('keyword') != "") {
            $menus = $menus->where('menus.name', 'like', '%' . $request->keyword . '%');
            

        }
        $menus = $menus->paginate(10);
        return view('admin.menu.index', compact('menus'));
    }

    public function create()
    {
        $menus = Menu::whereNull('parent_id')->get();
        return view('admin.menu.create', compact('menus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'url' => 'required|string',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'nullable|integer',
            'status' => 'nullable|boolean',
        ]);

        Menu::create($validated);

        return redirect()->route('admin.menus.index')->with('success', 'Menu created successfully.');
    }

    public function edit(Menu $menu)
    {
        $menus = Menu::whereNull('parent_id')->get();
        return view('admin.menu.edit', compact('menu', 'menus'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'url' => 'required|string',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'nullable|integer',
            'status' => 'nullable|boolean',
        ]);

        $menu->update($validated);

        return redirect()->route('admin.menus.index')->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('admin.menus.index')->with('success', 'Menu deleted successfully.');
    }

    public function getDepth($parentId = null, $depth = 0)
    {
        $menus = Menu::where('parent_id', $parentId)->get();

        foreach ($menus as $menu) {
            $menu->depth = $depth;
            $menu->save();
            $this->getDepth($menu->id, $depth + 1); 
        }

        return $menus;
    }
}
