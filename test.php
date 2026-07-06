<?php $cart = [["id" => 1]]; foreach ($cart as &$item) {} $cart[] = ["id" => 2]; $s = serialize($cart); $cart2 = unserialize($s); var_dump($cart2);
