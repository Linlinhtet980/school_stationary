<?php
$errors = [];
$dirs = ['app', 'routes'];

foreach ($dirs as $dir) {
    if (!is_dir(__DIR__ . '/' . $dir)) continue;
    
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__ . '/' . $dir));
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $output = [];
            $return_var = 0;
            exec('php -l "' . escapeshellarg($file->getPathname()) . '" 2>&1', $output, $return_var);
            if ($return_var !== 0) {
                $errors[$file->getPathname()] = implode("\n", $output);
            }
        }
    }
}

if (empty($errors)) {
    echo "No syntax errors found in app and routes directories.\n";
} else {
    foreach ($errors as $file => $err) {
        echo "Error in $file:\n$err\n\n";
    }
}
