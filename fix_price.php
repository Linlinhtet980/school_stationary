<?php
// Fix price display - replace $item->price with $item->price_range in card price displays
$dir = __DIR__ . '/resources/views/customer';
$files = ['home.blade.php', 'shop.blade.php', 'bestsellers.blade.php', 'new_arrivals.blade.php', 'b2s_deals.blade.php'];

foreach($files as $fname) {
    $f = $dir . '/' . $fname;
    $c = file_get_contents($f);
    
    // Replace number_format($item->price) Ks with $item->price_range
    $new = str_replace(
        ['{{ number_format($item->price) }} Ks', '{{ $item->price_range }}'],
        ['{{ $item->price_range }}', '{{ $item->price_range }}'],
        $c
    );
    
    // Fix wishlist price display
    $new = str_replace(
        'number_format($wishlistItem->item->price)',
        '$wishlistItem->item->price_range',
        $new
    );
    $new = str_replace(
        'number_format($wish->item->price)',
        '$wish->item->price_range',
        $new
    );
    
    if ($c !== $new) {
        file_put_contents($f, $new);
        echo "Updated: $fname\n";
    } else {
        echo "No change: $fname\n";
    }
}

echo "Done";
