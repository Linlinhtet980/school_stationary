<?php
$cssFilePath = __DIR__ . '/public/css/customer/views/prototype.css';
$viewsDir = __DIR__ . '/resources/views/customer';
$outputDir = __DIR__ . '/public/css/customer/views';

$content = file_get_contents($cssFilePath);
$parts = preg_split('/\/\* Extracted from (.*?) \*\//', $content, -1, PREG_SPLIT_DELIM_CAPTURE);

for ($i = 1; $i < count($parts); $i += 2) {
    $filename = trim($parts[$i]);
    $cssContent = trim($parts[$i+1]);
    if (empty($cssContent)) continue;
    
    $cssFilename = str_replace('.blade.php', '.css', $filename);
    
    // Append instead of overwrite because some files (like shop.css, checkout.css) might already exist from the manual extraction
    file_put_contents($outputDir . '/' . $cssFilename, "\n/* Inline Styles */\n" . $cssContent . "\n", FILE_APPEND);
    echo "Appended to CSS file: $cssFilename\n";
    
    $targetFile = $viewsDir . '/' . $filename;
    if (file_exists($targetFile)) {
        $viewContent = file_get_contents($targetFile);
        $linkTag = '<link rel="stylesheet" href="{{ asset(\'css/customer/views/' . $cssFilename . '\') }}">';
        if (strpos($viewContent, $linkTag) === false) {
            $pushBlock = "\n@push('styles')\n    " . $linkTag . "\n@endpush\n";
            if (preg_match('/@extends\([^)]+\)/', $viewContent, $match, PREG_OFFSET_CAPTURE)) {
                $insertPos = $match[0][1] + strlen($match[0][0]);
                $viewContent = substr_replace($viewContent, $pushBlock, $insertPos, 0);
            } else {
                $viewContent = $pushBlock . $viewContent;
            }
            file_put_contents($targetFile, $viewContent);
            echo "Injected @push('styles') into $filename\n";
        }
    }
}
