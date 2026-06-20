<?php

function splitAndLinkCss($masterCssPath, $outputDir, $viewsDir, $layoutFile, $assetPrefix, $isAdmin = false) {
    if (!file_exists($masterCssPath)) {
        echo "File not found: $masterCssPath\n";
        return;
    }

    if (!is_dir($outputDir)) {
        mkdir($outputDir, 0777, true);
    }

    $content = file_get_contents($masterCssPath);
    // Only split on lines that end with .css
    $parts = preg_split('/\/\* --- (.*?\.css) --- \*\//', $content, -1, PREG_SPLIT_DELIM_CAPTURE);

    $globalStyles = $parts[0];
    $layoutLinks = [];

    for ($i = 1; $i < count($parts); $i += 2) {
        $cssFilename = trim($parts[$i]);
        $cssContent = trim($parts[$i+1]);
        
        if (empty($cssContent)) continue;
        
        $cssOutputPath = $outputDir . '/' . $cssFilename;
        file_put_contents($cssOutputPath, "/* $cssFilename */\n" . $cssContent . "\n");
        echo "Created CSS file: $cssOutputPath\n";

        $assetUrl = "{{ asset('$assetPrefix/$cssFilename') }}";
        $linkTag = '<link rel="stylesheet" href="' . $assetUrl . '">';

        // Check if it's a layout/global file
        if (in_array($cssFilename, ['global.css', 'customer.css', 'layout.css', 'admin.css', 'theme.css', 'prototype.css'])) {
            $layoutLinks[] = $linkTag;
            continue;
        }

        // It's a view specific file.
        // e.g., shop.css -> shop.blade.php
        // items_create.css -> items/create.blade.php
        $bladeName = str_replace('.css', '.blade.php', $cssFilename);
        if ($isAdmin) {
            $bladeName = str_replace('_', '/', $bladeName);
        }
        
        // Find the blade file
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));
        $targetFile = null;
        foreach($files as $f) {
            if ($f->isFile() && str_ends_with(str_replace('\\', '/', $f->getPathname()), $bladeName)) {
                $targetFile = $f->getPathname();
                break;
            }
        }

        if ($targetFile) {
            $viewContent = file_get_contents($targetFile);
            if (strpos($viewContent, $assetUrl) === false) {
                $pushBlock = "\n@push('styles')\n    " . $linkTag . "\n@endpush\n";
                // Insert after @extends
                if (preg_match('/@extends\([^\)]+\)/', $viewContent, $match, PREG_OFFSET_CAPTURE)) {
                    $insertPos = $match[0][1] + strlen($match[0][0]);
                    $viewContent = substr_replace($viewContent, $pushBlock, $insertPos, 0);
                    file_put_contents($targetFile, $viewContent);
                    echo "Injected @push('styles') into $bladeName\n";
                } else {
                    file_put_contents($targetFile, $pushBlock . $viewContent);
                    echo "Prepended @push('styles') to $bladeName\n";
                }
            }
        } else {
            echo "Warning: Could not find blade file for $bladeName\n";
        }
    }

    // Link layout CSS in the layout file
    if (!empty($layoutLinks)) {
        $layoutContent = file_get_contents($layoutFile);
        $linksString = implode("\n    ", $layoutLinks) . "\n";
        
        // Let's just insert them before </head> if not already there
        foreach ($layoutLinks as $link) {
            if (strpos($layoutContent, $link) === false) {
                $layoutContent = str_replace('</head>', "    $link\n</head>", $layoutContent);
            }
        }
        file_put_contents($layoutFile, $layoutContent);
        echo "Updated layout file with master links: " . basename($layoutFile) . "\n";
    }

    // After splitting successfully, we don't delete the master css entirely, just keep the global parts?
    // User wants to completely separate them into folders. We will leave customer_master.css alone as a backup, 
    // or we can remove the old link from the layout.
    $oldLink = '<link rel="stylesheet" href="{{ asset(\'css/' . basename($masterCssPath) . '\') }}">';
    $layoutContent = file_get_contents($layoutFile);
    $layoutContent = str_replace($oldLink, '', $layoutContent);
    file_put_contents($layoutFile, $layoutContent);
}

splitAndLinkCss(
    __DIR__ . '/public/css/customer_master.css', 
    __DIR__ . '/public/css/customer/views', 
    __DIR__ . '/resources/views/customer', 
    __DIR__ . '/resources/views/layouts/customer.blade.php', 
    'css/customer/views',
    false
);

splitAndLinkCss(
    __DIR__ . '/public/css/admin_master.css', 
    __DIR__ . '/public/css/admin/views', 
    __DIR__ . '/resources/views/admin', 
    __DIR__ . '/resources/views/layouts/admin.blade.php', 
    'css/admin/views',
    true
);

echo "Splitting process finished.\n";
