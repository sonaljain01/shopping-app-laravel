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

    public function updateOrder(Request $request)
    {
        // $order = $request->input('order');

        // // foreach ($order as $menuData) {
        // //     $menu = Menu::find($menuData['id']);
        // //     $menu->order = $menuData['position'];
        // //     $menu->save();
        // // }
        // foreach ($order as $index => $menu) {
        //     Menu::where('id', $menu['id'])->update(['order' => $index + 1]);
        // }
        // // $menu = Menu::findOrFail($request->item_id);
        // // $menu->parent_id = $request->parent_id; // Update the parent_id field
        // // $menu->save();

        // return response()->json(['success' => true, 'message' => 'Menu order updated successfully.']);
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

}
