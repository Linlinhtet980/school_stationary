<?php
$dir = new RecursiveDirectoryIterator('resources/views/admin');
$ite = new RecursiveIteratorIterator($dir);
foreach($ite as $f) {
    if(strpos($f->getFilename(), '.blade.php') !== false) {
        $c = file_get_contents($f->getPathname());
        if(strpos($c, "@push('styles')") === false && strpos($c, "@push(\"styles\")") === false) {
            echo $f->getPathname() . "\n";
        }
    }
}
