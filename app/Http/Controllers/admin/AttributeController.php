<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attribute;
use Validator;
class AttributeController extends Controller
{
    public function index(){
        $attributes = Attribute::latest();
        return view('admin.attributes.index', [
            'attributes' => $attributes->paginate(10)
        ]);
    }

    public function create(){
        return view('admin.attributes.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
           
        ]);

        if($validator->passes()){
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

}
