<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $c = new App\Http\Controllers\Customer\CartController();
    $res = $c->getItemsAjax();
    echo "Response:\n";
    echo $res->getContent();
} catch (\Exception $e) {
    echo "Exception:\n";
    echo $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine();
}
