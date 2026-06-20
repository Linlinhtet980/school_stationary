<?php
$dir = __DIR__ . '/resources/views';
$cssFile = __DIR__ . '/public/css/customer/prototype.css';
$adminCssFile = __DIR__ . '/public/css/layouts/admin.css';

$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

$customerCss = "";
$adminCss = "";

foreach($files as $f) {
    if ($f->isFile() && $f->getExtension() === 'php') {
        $path = $f->getPathname();
        $c = file_get_contents($path);
        
        // Extract <style>...</style>
        preg_match_all('/<style[^>]*>(.*?)<\/style>/is', $c, $matches);
        
        if (!empty($matches[0])) {
            $extracted = "";
            foreach($matches[1] as $styleContent) {
                $extracted .= "/* Extracted from " . basename($path) . " */\n";
                $extracted .= $styleContent . "\n\n";
            }
            
            if (strpos($path, 'admin') !== false) {
                $adminCss .= $extracted;
            } else {
                $customerCss .= $extracted;
            }
            
            // Remove styles from original file
            $new = preg_replace('/<style[^>]*>(.*?)<\/style>/is', '', $c);
            file_put_contents($path, $new);
            echo "Extracted styles from: " . basename($path) . "\n";
        }
    }
}

file_put_contents($cssFile, "\n\n/* === AUTO-EXTRACTED STYLES === */\n" . $customerCss, FILE_APPEND);
file_put_contents($adminCssFile, "\n\n/* === AUTO-EXTRACTED STYLES === */\n" . $adminCss, FILE_APPEND);

echo "Extraction complete!\n";
