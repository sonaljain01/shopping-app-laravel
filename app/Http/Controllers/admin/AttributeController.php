<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attribute;
use Validator;
use App\Models\AttributeValue;
class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::latest();
        return view('admin.attributes.index', [
            'attributes' => $attributes->paginate(10)
        ]);
    }

    public function create()
    {
        return view('admin.attributes.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',

        ]);

        if ($validator->passes()) {
            $attribute = new Attribute();
            $attribute->name = $request->name;

            $attribute->save();

            $request->session()->flash('success', 'Attribute created successfully');
            return redirect()->route('attributes.index');
        } else {

            $request->session()->flash('error', $validator->errors()->first());
            return redirect()->route('attributes.index');
        }
    }

    public function showAddValuesForm($attributeId)
    {
        $attribute = Attribute::findOrFail($attributeId);
        return view('admin.attributes.add-values', ['attribute' => $attribute]);
    }

    public function storeValues(Request $request, $attributeId)
    {
        $request->validate([
            'values' => 'required|array',
            'values.*' => 'required|string|max:255',
        ]);

        $attribute = Attribute::findOrFail($attributeId);

        foreach ($request->values as $value) {
            $attribute->values()->create(['value' => $value]);
        }

        return redirect()->route('attributes.index')->with('success', 'Values added successfully');
    }

    public function getAttributeValues($attributeId)
    {
        $values = AttributeValue::where('attribute_id', $attributeId)->pluck('value');
        return response()->json($values);
    }

    public function showAttributesForm()
    {
        $attributes = Attribute::with('values')->get();
        dd($attributes);

        return view('admin.attributes.form', compact('attributes'));
    }

    public function edit($id)
    {
        $attribute = Attribute::findOrFail($id);

        // Return the edit view with the attribute data
        return view('admin.attributes.edit', [
            'attribute' => $attribute
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255', // Ensure the name is required and a string
        ]);

        // If validation passes
        if ($validator->passes()) {
            // Find the attribute by its ID
            $attribute = Attribute::findOrFail($id);

            // Update the attribute's name
            $attribute->name = $request->name;

            // Save the changes
            $attribute->save();

            // Flash success message to the session
            $request->session()->flash('success', 'Attribute updated successfully');

            // Redirect to the attributes index page
            return redirect()->route('attributes.index');
        } else {
            // If validation fails, flash error message to the session
            $request->session()->flash('error', $validator->errors()->first());

            // Redirect back to the edit form
            return redirect()->route('attributes.edit', ['id' => $id]);
        }
    }

    public function destroy($id)
    {
        $attribute = Attribute::findOrFail($id);

        // Deleting associated values
        foreach ($attribute->values as $value) {
            $value->delete();
        }

        // Delete the attribute
        $attribute->delete();

        return redirect()->route('attributes.index')->with('success', 'Attribute deleted successfully');
    }

}
