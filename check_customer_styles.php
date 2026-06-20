<?php

$dir = new RecursiveDirectoryIterator('resources/views/customer');
$ite = new RecursiveIteratorIterator($dir);

foreach($ite as $f) {
    if(strpos($f->getFilename(), '.blade.php') !== false) {
        $path = $f->getPathname();
        $content = file_get_contents($path);
        
        // Skip partials or layouts
        if (strpos($path, 'partials') !== false || strpos($path, 'layouts') !== false) continue;
        
        $relativePath = str_replace(realpath('resources/views/customer') . DIRECTORY_SEPARATOR, '', realpath($path));
        $relativePath = str_replace('.blade.php', '', $relativePath);
        
        if (strpos($content, "@push('styles')") === false && strpos($content, "@push(\"styles\")") === false) {
            echo "Missing styles in: " . $relativePath . "\n";
        }
    }
}
