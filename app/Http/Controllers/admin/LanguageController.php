<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use Illuminate\Pagination\LengthAwarePaginator;

class LanguageController extends Controller
{
    public function index()
    {
        $languages = Language::orderBy('id', 'asc')->paginate(10);
        return view('admin.languages.index', compact('languages'));
    }

    public function create()
    {
        return view('admin.languages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:languages',
        ]);

        $language = Language::create($request->only('name', 'code'));
        $languageCode = $language->code;

        $newLangPath = resource_path("lang/{$languageCode}.json");
        file_put_contents($newLangPath, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return redirect()->route('languages.index')->with('success', 'Language added successfully!');
    }
    public function edit($id)
    {
        $language = Language::findOrFail($id);
        $languageCode = $language->code;

        $baseLangPath = resource_path('lang/en.json');
        $selectedLangPath = resource_path("lang/{$languageCode}.json");

        if (!file_exists($baseLangPath)) {
            return redirect()->back()->withErrors('Base language file not found.');
        }

        $baseTranslations = json_decode(file_get_contents($baseLangPath), true);

        if (!file_exists($selectedLangPath)) {
            file_put_contents($selectedLangPath, json_encode([]));
        }

        $selectedTranslations = json_decode(file_get_contents($selectedLangPath), true);
        
        return view('admin.languages.edit', [
            'languageCode' => $languageCode,
            'translations' => $selectedTranslations,
            'baseTranslations' => $baseTranslations,
            
        ]);


    }


    public function update(Request $request, $languageCode)
    {
        $language = Language::where('code', $languageCode)->firstOrFail();

        $translations = $request->input('translations');

        // Save the updated translations to the file
        $langFilePath = resource_path("lang/{$languageCode}.json");
        file_put_contents($langFilePath, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return redirect()->route('languages.index')->with('success', 'Translations updated successfully!');
    }
    public function destroy($id)
    {
        $language = Language::findOrFail($id);
        $langPath = resource_path("lang/{$language->code}.json");

        if (file_exists($langPath)) {
            unlink($langPath); // Delete the language file
        }

        $language->delete(); // Remove the language from the database

        return redirect()->route('languages.index')->with('success', 'Language deleted successfully!');
    }


}
