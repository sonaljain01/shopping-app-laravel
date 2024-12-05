<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TranslationRequest;
use App\Models\Translation;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    public function index()
    {
        // $translations = Translation::all();
        // // $translations = Translation::paginate(10);
        // $groupTranslations = $translations->groupBy('language');
        // return view('admin.translation.index', compact('groupTranslations'));
        $translations = Translation::orderBy('language')->paginate(10);

        return view('admin.translation.index', compact('translations'));
    }

    public function create(Request $request)
    {
        return view('admin.translation.create');
    }

    public function store(TranslationRequest $request)
    {
        $validatedData = $request->validated();

        $translation = Translation::create($validatedData);

        return redirect()->route('translations.index')->with('success', 'Translation created successfully.');
    }

    public function edit($id)
    {
        $translation = Translation::find($id);
        return view('admin.translation.edit', compact('translation'));
    }

    public function update(TranslationRequest $request, $id)
    {
        $validatedData = $request->validated();

        $translation = Translation::findOrFail($id);
        $translation->update($validatedData);

        return redirect()->route('translations.index')->with('success', 'Translation updated successfully.');
    }

    public function destroy($id, Request $request)
    {
        $translation = Translation::findOrFail($id);
        $translation->delete();
        $request->session()->flash('success', 'Translation deleted successfully!');
        return response()->json([
            'status' => true,
            'message' => 'Translation deleted successfully'
        ]);
    }
}
