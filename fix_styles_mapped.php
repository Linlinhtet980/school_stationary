<?php

$dir = new RecursiveDirectoryIterator('resources/views/admin');
$ite = new RecursiveIteratorIterator($dir);

$mapping = [
    'banners\edit' => 'banners_create.css',
    'brands\index' => 'brands.css',
    'bundles\create' => 'bundles.css',
    'bundles\edit' => 'bundles.css',
    'bundles\index' => 'bundles.css',
    'categories\index' => 'categories.css',
    'customers\index' => 'customers.css',
    'customers\show' => 'customers.css',
    'orders\index' => 'orders.css',
    'orders\show' => 'orders.css',
    'reviews\index' => 'reviews.css',
    'staff\create' => 'staff_form.css',
    'staff\edit' => 'staff_form.css',
    'types\index' => 'types.css',
];

foreach($ite as $f) {
    if(strpos($f->getFilename(), '.blade.php') !== false) {
        $path = $f->getPathname();
        $content = file_get_contents($path);
        
        // Skip partials
        if (strpos($path, 'partials') !== false) continue;
        
        $relativePath = str_replace(realpath('resources/views/admin') . DIRECTORY_SEPARATOR, '', realpath($path));
        $relativePath = str_replace('.blade.php', '', $relativePath);
        
        if (isset($mapping[$relativePath])) {
            $cssName = $mapping[$relativePath];
            $cssPath = 'public/css/admin/views/' . $cssName;
            
            if (strpos($content, "@push('styles')") === false && strpos($content, "@push(\"styles\")") === false) {
                if (file_exists($cssPath)) {
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
                    echo "File not found: $cssPath\n";
                }
            }
        }
    }
}
