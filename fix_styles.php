<?php

$dir = new RecursiveDirectoryIterator('resources/views/admin');
$ite = new RecursiveIteratorIterator($dir);

foreach($ite as $f) {
    if(strpos($f->getFilename(), '.blade.php') !== false) {
        $path = $f->getPathname();
        $content = file_get_contents($path);
        
        // Skip partials
        if (strpos($path, 'partials') !== false) continue;
        
        if(strpos($content, "@push('styles')") === false && strpos($content, "@push(\"styles\")") === false) {
            
            // Determine the matching CSS filename
            $relativePath = str_replace(realpath('resources/views/admin') . DIRECTORY_SEPARATOR, '', realpath($path));
            $relativePath = str_replace('.blade.php', '', $relativePath);
            $cssName = str_replace(DIRECTORY_SEPARATOR, '_', $relativePath) . '.css';
            
            $cssPath = 'public/css/admin/views/' . $cssName;
            
            if (file_exists($cssPath)) {
                // We have a corresponding CSS file, let's inject it!
                $pushBlock = "\n@push('styles')\n    <link rel=\"stylesheet\" href=\"{{ asset('css/admin/views/{$cssName}') }}\">\n@endpush\n";
                
                // Insert after @extends
                $content = preg_replace("/(@extends\(['\"]layouts\.admin['\"]\))/i", "$1" . $pushBlock, $content, 1, $count);
                
                if ($count > 0) {
                    file_put_contents($path, $content);
                    echo "Added push styles to $relativePath for $cssName\n";
                } else {
                    echo "Could not find @extends in $relativePath\n";
                }
            } else {
                echo "No CSS file found for $relativePath at $cssPath\n";
            }
        }
    }
}
