<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$dir = new RecursiveDirectoryIterator('resources/views/admin');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/.*\.blade\.php$/', RegexIterator::GET_MATCH);

$invalidRoutes = [];

foreach($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    preg_match_all('/route\([\'"]([^\'"]+)[\'"]/', $content, $matches);
    
    foreach($matches[1] as $route) {
        if(!Route::has($route)) {
            $invalidRoutes[] = "File: " . basename($path) . " - Route: $route";
        }
    }
}

if(empty($invalidRoutes)) {
    echo "All routes are valid!\n";
} else {
    echo implode("\n", array_unique($invalidRoutes));
}
