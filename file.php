<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Language;
use Illuminate\Support\Facades\Http;
class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $languages = Language::all();
        return view('admin.languages.index', compact('languages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.languages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:languages',
        ]);

        // Save the new language
        $language = Language::create($request->only('name', 'code'));
        $languageCode = $language->code; // Assign the value of $language->code to $languageCode

        // Create a new language file
        $newLangPath = resource_path("lang/{$languageCode}.json");
        file_put_contents($newLangPath, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Synchronize all language files with the base language
        $this->synchronizeLanguageFiles();
        return redirect()->route('languages.index')->with('success', 'Language added successfully!');
    }

    private function translate($text, $targetLang)
    {
        try {
            $apiKey = env('GOOGLE_TRANSLATE_API_KEY'); // Store your API Key in .env

            // Make the API call
            $response = Http::get('https://translation.googleapis.com/language/translate/v2', [
                'q' => $text,
                'target' => $targetLang,
                'source' => 'en', // Source language
                'key' => $apiKey,
            ]);

            // Parse the response
            $responseData = $response->json();
            return $responseData['data']['translations'][0]['translatedText'] ?? $text;
        } catch (\Exception $e) {
            return $text;
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->synchronizeLanguageFiles();

        $language = Language::findOrFail($id); // Retrieve the language by ID
        $languageCode = $language->code;      // Extract the language code
    
        $baseLangPath = resource_path('lang/en.json'); // Base language path
        $selectedLangPath = resource_path("lang/{$languageCode}.json"); // Target language path
    
        // Ensure base file exists
        if (!file_exists($baseLangPath)) {
            return redirect()->back()->withErrors('Base language file not found.');
        }
    
        $baseTranslations = json_decode(file_get_contents($baseLangPath), true);
    
        // Check if the selected language file exists
        if (!file_exists($selectedLangPath)) {
            // Create an empty file if it doesn't exist
            file_put_contents($selectedLangPath, json_encode([]));
        }
    
        $selectedTranslations = json_decode(file_get_contents($selectedLangPath), true);
    
        return view('admin.languages.edit', [
            'languageCode' => $languageCode,
            'translations' => $selectedTranslations,
            'baseTranslations' => $baseTranslations,
        ]);
    }

    private function synchronizeLanguageFiles()
    {
        $baseLangPath = resource_path('lang/en.json');
        $baseTranslations = file_exists($baseLangPath)
            ? json_decode(file_get_contents($baseLangPath), true)
            : [];

        $languageFiles = glob(resource_path('lang/*.json'));

        foreach ($languageFiles as $file) {
            if (basename($file) === 'en.json') {
                continue; // Skip the base file
            }

            $languageCode = pathinfo($file, PATHINFO_FILENAME);
            $currentTranslations = json_decode(file_get_contents($file), true) ?? [];

            // Add missing keys from base file
            foreach ($baseTranslations as $key => $value) {
                if (!array_key_exists($key, $currentTranslations)) {
                    $currentTranslations[$key] = $this->translate($value, $languageCode);
                }
            }

            // Save updated translations
            file_put_contents($file, json_encode($currentTranslations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $language = Language::findOrFail($id);
        $languageCode = $language->code;
        $request->validate([
            'translations' => 'required|array',
        ]);

        $newTranslations = $request->input('translations');
        $langFilePath = resource_path("lang/{$languageCode}.json");

        // Save the updated translations to the file
        file_put_contents($langFilePath, json_encode($newTranslations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return redirect()->route('languages.index')->with('success', 'Translations updated successfully!');
    }
    public function synchronize()
    {
        $this->synchronizeLanguageFiles();
        return redirect()->route('languages.index')->with('success', 'Languages synchronized successfully!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
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
