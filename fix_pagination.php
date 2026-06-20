<?php
$dir = __DIR__ . '/resources/views/customer';
$files = glob($dir . '/*.blade.php');
foreach($files as $f) {
    $c = file_get_contents($f);
    $new = str_replace("links('pagination::bootstrap-4')", "links('vendor.pagination.pure-css')", $c);
    if($c !== $new) {
        file_put_contents($f, $new);
        echo 'Updated: ' . basename($f) . PHP_EOL;
    }
}
