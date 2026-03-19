<?php
header('Content-Type: application/json');

$productsFile = "products.json";

if (file_exists($productsFile)) {
    echo file_get_contents($productsFile);
} else {
    echo json_encode([]);
}
?>
