<?php
function splitCss($cssFilePath, $viewsDir, $outputDir, $assetPrefix) {
    if (!file_exists($cssFilePath)) return;
    
    $content = file_get_contents($cssFilePath);
    
    // Split by the extraction comment pattern
    $parts = preg_split('/\/\* Extracted from (.*?) \*\//', $content, -1, PREG_SPLIT_DELIM_CAPTURE);
    
    // Create output directory if it doesn't exist
    if (!is_dir($outputDir)) {
        mkdir($outputDir, 0777, true);
    }
    
    // Maintain what's left in the main CSS file
    $mainCss = $parts[0];
    
    for ($i = 1; $i < count($parts); $i += 2) {
        $filename = trim($parts[$i]);
        $cssContent = trim($parts[$i+1]);
        
        if (empty($cssContent)) continue;
        
        // Save to individual CSS file (replace .blade.php with .css)
        $cssFilename = str_replace('.blade.php', '.css', $filename);
        $cssOutputPath = $outputDir . '/' . $cssFilename;
        file_put_contents($cssOutputPath, "/* Styles for $filename */\n" . $cssContent . "\n");
        echo "Created CSS file: $cssOutputPath\n";
        
        // Find the blade file to inject the link
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));
        $targetFile = null;
        foreach($files as $f) {
            if ($f->isFile() && basename($f->getPathname()) === $filename) {
                $targetFile = $f->getPathname();
                break;
            }
        }
        
        if ($targetFile) {
            $viewContent = file_get_contents($targetFile);
            
            // Check if link is already injected
            $linkTag = '<link rel="stylesheet" href="{{ asset(\'' . $assetPrefix . '/' . $cssFilename . '\') }}">';
            if (strpos($viewContent, $linkTag) === false) {
                $pushBlock = "\n@push('styles')\n    " . $linkTag . "\n@endpush\n";
                // Prepend to file (after extends/section if possible, but prepending is tricky. 
                // Better to just insert it after @extends if it exists)
                if (preg_match('/@extends\([^)]+\)/', $viewContent, $match, PREG_OFFSET_CAPTURE)) {
                    $insertPos = $match[0][1] + strlen($match[0][0]);
                    $viewContent = substr_replace($viewContent, $pushBlock, $insertPos, 0);
                } else {
                    $viewContent = $pushBlock . $viewContent;
                }
                file_put_contents($targetFile, $viewContent);
                echo "Injected @push('styles') into $filename\n";
            }
        } else {
            echo "Warning: Could not find blade file $filename\n";
        }
    }
    
    // Save the remaining main CSS (without extracted blocks)
    file_put_contents($cssFilePath, trim($mainCss) . "\n");
}

splitCss(__DIR__ . '/public/css/customer/prototype.css', __DIR__ . '/resources/views', __DIR__ . '/public/css/customer/views', 'css/customer/views');
splitCss(__DIR__ . '/public/css/layouts/admin.css', __DIR__ . '/resources/views/admin', __DIR__ . '/public/css/admin/views', 'css/admin/views');

echo "CSS Splitting Complete.\n";
