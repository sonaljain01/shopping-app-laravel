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
        $menus = Menu::whereNull('parent_id')
            ->with('children')
            ->orderBy('order') // Order by the 'order' column to display correctly
            ->get();
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
            'location' => 'required|in:header,footer,both',
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
            'location' => 'required|in:header,footer,both',
        ]);

        $menu->update($validated);

        return redirect()->route('admin.menus.index')->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu, Request $request)   
    {
        if ($menu->children()->exists()) {
            $request->session()->flash('error', 'Menu cannot be deleted as it has child menus.');
            return redirect()->route('admin.menus.index')->with('error', 'Menu cannot be deleted as it has child menus.');
        }
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

    public function updateOrder(Request $request)
    {
        $orderData = $request->order;
        foreach ($orderData as $item) {
            $menu = Menu::find($item['id']);
            $menu->update([
                'order' => $item['order'],
                'parent_id' => $item['parent_id']
            ]);
        }

        return response()->json(['status' => 'success', 'message' => 'Menu order updated successfully']);
    }

    public function manageLocations()
    {
        // Fetch only parent menus
        $menus = Menu::whereNull('parent_id')->get();

        return view('admin.menu.manage-locations', compact('menus'));
    }

    public function updateLocations(Request $request)
    {
        $data = $request->input('locations', []);

        foreach ($data as $menuId => $location) {
            Menu::where('id', $menuId)->update(['location' => $location]);
        }

        return redirect()->route('admin.menus.manageLocations')->with('success', 'Locations updated successfully.');
    }

}
