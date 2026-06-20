<?php

$dir = new RecursiveDirectoryIterator('resources/views/customer');
$ite = new RecursiveIteratorIterator($dir);

foreach($ite as $f) {
    if(strpos($f->getFilename(), '.blade.php') !== false) {
        $path = $f->getPathname();
        $content = file_get_contents($path);
        
        // Find orphaned script tag at the end
        if (preg_match('/@push\(\'scripts\'\)\s*@endpush\s*(<script>.*?<\/script>)\s*$/is', $content, $matches)) {
            $scriptContent = $matches[1];
            $newPushBlock = "@push('scripts')\n" . $scriptContent . "\n@endpush\n";
            $content = preg_replace('/@push\(\'scripts\'\)\s*@endpush\s*(<script>.*?<\/script>)\s*$/is', $newPushBlock, $content);
            file_put_contents($path, $content);
            echo "Fixed orphaned script in: " . $path . "\n";
        }
    }
}
