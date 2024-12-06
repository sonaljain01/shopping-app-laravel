<?php
namespace App\Services;

use Illuminate\Contracts\Translation\Loader;

class DatabaseTranslationLoader implements Loader
{
    public function load($locale, $group, $namespace = null)
    {
        return \App\Models\Translation::where('language', $locale)
            ->where('key', 'like', "$group.%")
            ->pluck('value', 'key');
            
        
    }

    public function addNamespace($namespace, $hint) {}

    public function addJsonPath($path) {}

    public function namespaces() {
        return [];
    }
}
