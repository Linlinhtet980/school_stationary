<?php
$cities = [
    ["region_name" => "Naypyidaw", "base_fee" => 3000, "extra_fee_per_item" => 500],
    ["region_name" => "Taunggyi", "base_fee" => 3500, "extra_fee_per_item" => 500],
    ["region_name" => "Mawlamyine", "base_fee" => 3000, "extra_fee_per_item" => 500],
    ["region_name" => "Pathein", "base_fee" => 3000, "extra_fee_per_item" => 500],
    ["region_name" => "Monywa", "base_fee" => 3000, "extra_fee_per_item" => 500],
    ["region_name" => "Magway", "base_fee" => 3000, "extra_fee_per_item" => 500]
];
foreach($cities as $c) {
    \App\Models\ShippingRate::updateOrCreate(["region_name" => $c["region_name"]], $c);
}
echo "Done\n";
